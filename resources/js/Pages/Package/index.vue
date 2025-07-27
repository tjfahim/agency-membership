<template>
  <MainLayout>
    <!-- Success flash message -->
    <div v-if="showMessage" class="alert alert-success mb-4">
      {{ flash?.success }}
    </div>

    <div class="p-6">
      <!-- Popup modal component -->
      <ViewPackage
        :companyPackage="companyPackage"
        :companySoftwares="companySoftwares"
        :popUpModalView="popUpModalView"
        @close="popUpModalView = false"
      />

      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Package List</h1>
        <Link
          href="/package/create"
          class="block px-4 py-2 ml-5 !text-slate-200 !no-underline rounded bg-blue-600 hover:bg-gray-700 text-lg"
        >
          Create Package
        </Link>
      </div>

      <!-- No packages message -->
      <div v-if="rows.length === 0" class="text-gray-500">
        No packages found.
      </div>

      <!-- Data table -->
      <vue-good-table
        v-else
        :columns="columns"
        :rows="rows"
        :search-options="{ enabled: true, placeholder: 'Search by name...' }"
        :pagination-options="{
          enabled: true,
          perPage: 10,
          perPageDropdown: [5, 10, 20, 50, 100],
          dropdownAllowAll: false
        }"
        :sort-options="{ enabled: true }"
        :styleClass="'vgt-table bordered'"
      >
        <template #table-row="{ row, column }">
          <td v-if="column.field === 'actions'" class="text-center py-2">
            <div class="flex justify-center space-x-4">
              <!-- Show popup on eye button click -->
              <button
                @click="showPackage(row)"
                class="text-blue-600 hover:underline"
                title="View Package"
              >
                <i class="las la-eye text-green-500 text-xl"></i>
              </button>

              <Link
                :href="`/package/${row.id}/edit`"
                class="text-yellow-600 hover:underline"
                title="Edit Package"
              >
                <i class="las la-edit text-xl"></i>
              </Link>

              <button
                @click="deletePackage(row.id)"
                class="text-red-600 hover:underline"
                title="Delete Package"
              >
                <i class="las la-trash text-xl"></i>
              </button>
            </div>
          </td>
          <td v-else>
            {{ row[column.field] }}
          </td>
        </template>
      </vue-good-table>

      <!-- Pagination component if you want (optional, vue-good-table handles paging) -->
      <!--
      <Pagination
        v-if="pagination && pagination.total > pagination.per_page"
        :pagination="pagination"
        @change-page="changePage"
      />
      -->
    </div>
  </MainLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import axios from "axios";
import MainLayout from "@/components/layouts/MainLayout.vue";
import ViewPackage from "../../components/Package/view.vue";
import { defineProps, ref, computed, onMounted, watch } from "vue";
import { VueGoodTable } from "vue-good-table-next";
import "vue-good-table-next/dist/vue-good-table-next.css";

const props = defineProps({
  packages: Object, // expects paginated data: { data: [...] }
  flash: Object,
  pagination: Object,
});

const showMessage = ref(false);

const popUpModalView = ref(false);
const companyPackage = ref({});
const companySoftwares = ref([]);

// Show package popup modal with package details
const showPackage = async (myPackage) => {
  try {
    const response = await axios.get(`/package/${myPackage.id}`);
    companyPackage.value = response.data.ourPackage;
    companySoftwares.value = response.data.softwares;
    popUpModalView.value = true;
  } catch (err) {
    console.error("Failed to fetch package details", err);
  }
};

// Delete package
function deletePackage(id) {
  if (confirm("Are you sure you want to delete this package?")) {
    router.delete(`/package/${id}`, {
      onSuccess: () => {
        console.log("Deleted Successfully");
        // Optionally show flash message on success if backend sends it
      },
    });
  }
}

// Table columns definition
const columns = [
  { label: "Name", field: "name", sortable: true },
  { label: "Duration Type", field: "duration_type", sortable: true },
  { label: "Duration", field: "duration", sortable: true },
  { label: "Price", field: "price", sortable: true },
  { label: "Actions", field: "actions", sortable: false },
];

// Rows for table from props
const rows = computed(() => props.packages?.data ?? []);

onMounted(() => {
  if (props.flash?.success) {
    showFlash();
  }
});

const showFlash = () => {
  showMessage.value = true;
  setTimeout(() => {
    showMessage.value = false;
  }, 3000);
};

watch(
  () => props.flash?.success,
  (newVal) => {
    if (newVal) {
      showFlash();
    }
  }
);

// Optional: pagination change handler if you use your own Pagination component instead of vue-good-table's paging
const changePage = (page) => {
  router.get(route("package.index", { page }, { preserveState: true }));
};
</script>

<style>
.vgt-table.bordered td,
.vgt-table.bordered th {
  border: 1px solid #57514fa6;
}

.vgt-table.bordered td td {
  border: none;
}

table.vgt-table td {
  padding: 0.5em;
}
</style>
