<template>
  <MainLayout>
    <div v-if="showMessage" class="alert alert-success">
      {{ flash?.success }}
    </div>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Package List</h1>
            <div v-if="packages.length === 0" class="text-gray-500">
        No packages found.
      </div>

      <vue-good-table
  v-else
  :columns="columns"
  :rows="rows"
  :search-options="{ enabled: true, placeholder: 'Search by name...' }"
  :pagination-options="{
    enabled: true,
    perPage: 5,
    perPageDropdown: [5,10,20,100,500],
    dropdownAllowAll: false
  }"
  :sort-options="{ enabled: true }"
  :styleClass="tableClass"
>
  <template #table-row="{ row, column }">
    <td
      v-if="column.field === 'actions'"
      class="flex justify-center space-x-4 py-2 text-center"
    >
      <Link :href="`/package/${row.id}`" class="text-green-500 hover:text-green-700">
        <i class="las la-eye text-xl"></i>
      </Link>
      <Link :href="`/package/${row.id}/edit`" class="text-yellow-500 hover:text-yellow-700">
        <i class="las la-edit text-xl"></i>
      </Link>
      <button @click="deletePackage(row.id)" class="text-red-500 hover:text-red-700">
        <i class="las la-trash text-xl"></i>
      </button>
    </td>
    <td
      v-else
      
    >
      {{ row[column.field] }}
    </td>
  </template>
</vue-good-table>

    </div>
  </MainLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import MainLayout from "@/components/layouts/MainLayout.vue";
import { defineProps, ref, computed, onMounted, watch } from "vue";
import { VueGoodTable } from "vue-good-table-next";
import "vue-good-table-next/dist/vue-good-table-next.css";

const props = defineProps({
  packages: Object, // paginated data object
  flash: Object,
});

const showMessage = ref(false);

function deletePackage(id) {
  if (confirm("Are you sure you want to delete this package?")) {
    router.delete(`/package/${id}`, {
      onSuccess: () => {
        console.log("deleted Successfully");
      },
    });
  }
}

const columns = [
  { label: "Name", field: "name", sortable: true },
  { label: "Duration Type", field: "duration_type", sortable: true },
  { label: "Duration", field: "duration", sortable: true },
  { label: "Price", field: "price", sortable: true },
  { label: "Actions", field: "actions", sortable: false },
];

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
</script>
<style>
.vgt-table.bordered td, .vgt-table.bordered th {
    border: 1px solid #57514fa6;
}
.vgt-table.bordered td td {
    border: none;
}
table.vgt-table td {
 
    padding: 0.50em 0.50em 0.50em 0.50em;
   
 
}
</style>