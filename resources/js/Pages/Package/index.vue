<template>
<<<<<<< HEAD
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
=======
    <MainLayout>
        <div v-if="showMessage" class="alert alert-success">
            {{ flash?.success }}
        </div>
        <div class="p-6">
            <ViewPackage :companyPackage="companyPackage" :companySoftwares="companySoftwares"  :popUpModalView="popUpModalView"
                @close="popUpModalView=false"></ViewPackage>
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Package List</h1>
                <Link href="package/create"
                    class="block px-4 py-2 ml-5 !text-slate-200 !no-underline rounded bg-blue-600 hover:bg-gray-700 text-lg">
                Create Package
                </Link>
            </div>
            <div v-if="packages.length === 0" class="text-gray-500">
                No packages found.
            </div>
            <table class="w-full table-auto border-collapse border border-gray-300" v-else>
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Name</th>
                        <th class="border px-4 py-2 text-left">
                            Duration Type
                        </th>
                        <th class="border px-4 py-2 text-left">Duration</th>
                        <th class="border px-4 py-2 text-left">Price</th>
                        <th class="border px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(myPackage, index) in filteredPackage" :key="index" class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ myPackage.name }}</td>
                        <td class="border px-4 py-2">
                            {{ myPackage.duration_type }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ myPackage.duration }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ myPackage.price }} tk
                        </td>
                        <td
                            class="flex justify-center items-center border md:px-4 md:py-2 text-center space-x-2 md:space-x-2 xl:space-x-4">
                            <button @click="showPackage(myPackage)" class="text-blue-600 hover:underline "><i
                                    class="las la-eye md:text-md lg:text-lg xl:text-2xl text-green-500 mr-3"></i></button>
                            <Link :href="`/package/${myPackage.id}/edit`" class="text-yellow-600 hover:underline"><i
                                class="las la-edit md:text-md lg:text-lg xl:text-2xl"></i></Link>
                            <button @click="deletePackage(myPackage.id)" class="text-red-600 hover:underline">
                                <i class="las la-trash md:text-md lg:text-lg xl:text-2xl"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination v-if="packages.data.length > 0" :pagination="pagination" @change-page="changePage"></Pagination>
        </div>
    </MainLayout>
>>>>>>> origin/Enamul
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import axios from "axios";
import MainLayout from "@/components/layouts/MainLayout.vue";
import { defineProps, ref, computed, onMounted, watch } from "vue";
<<<<<<< HEAD
import { VueGoodTable } from "vue-good-table-next";
import "vue-good-table-next/dist/vue-good-table-next.css";

=======
import ViewPackage from "../../components/Package/view.vue";
>>>>>>> origin/Enamul
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
<<<<<<< HEAD

const columns = [
  { label: "Name", field: "name", sortable: true },
  { label: "Duration Type", field: "duration_type", sortable: true },
  { label: "Duration", field: "duration", sortable: true },
  { label: "Price", field: "price", sortable: true },
  { label: "Actions", field: "actions", sortable: false },
];

const rows = computed(() => props.packages?.data ?? []);
=======
const companyPackage = ref({});
const companySoftwares = ref([]);
const popUpModalView= ref(false);
const showPackage = async (myPackage) => {
    try {
        const response = await axios.get('/package/' + myPackage.id);
        console.log(response);
        companyPackage.value = response.data.ourPackage;
        companySoftwares.value = response.data.softwares;
        popUpModalView.value = true;
    }catch(err){
        console.log("Failed to fetch response",err);
    }
}
const filteredPackage = computed(() => {
    if (is_delete.value === true && deletedId.value !== null) {
        return props.packages.data.filter(
            (myPackage) => myPackage.id != deletedId.value
        );
    }
    return props.packages.data;
});
>>>>>>> origin/Enamul

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
<<<<<<< HEAD
=======
const changePage = (page) => {
    router.get(route('package.index', { page }, { preserveState: true }));
}
>>>>>>> origin/Enamul
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