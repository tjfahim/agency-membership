<template>
  <MainLayout>
    <div v-if="showMessage" class="alert alert-success">
      {{ flash?.success }}
    </div>
    <div class="p-6">
       <div class="flex justify-between items-center mb-4">
          <h1 class="text-2xl font-bold">Software List</h1>
          <Link 
            :href="`/software/create`" 
            class="block px-4 py-1 ml-5 !text-slate-300 !no-underline rounded bg-blue-700 hover:bg-gray-700 text-sm"
          >
            Create Software
          </Link>
        </div>

      <table class="w-full table-auto border-collapse border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-4 py-2 text-left">Name</th>
            <th class="border px-4 py-2 text-left">URL</th>
            <th class="border px-4 py-2 text-left">Active</th>
            <th class="border px-4 py-2 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="software in filteredSoftware" :key="software.id" class="hover:bg-gray-50">
            <td class="border px-4 py-2">{{ software.name }}</td>
            <td class="border px-4 py-2">
              <a :href="software.url" target="_blank" class="text-blue-500 hover:underline">
                {{ software.url }}
              </a>
            </td>
            <td class="border px-4 py-2">
              <span :class="software.is_active ? 'text-green-600' : 'text-red-500'">
                {{ software.is_active ? 'Yes' : 'No' }}
              </span>
            </td>
            <td class="flex justify-center items-center border md:px-4 md:py-2  text-center space-x-2 md:space-x-2 xl:space-x-4">
              <Link :href="`/software/${software.id}`" class="text-blue-600 hover:underline"><i
                class="las la-eye md:text-md lg:text-lg xl:text-2xl  text-green-500"></i></Link>
              <Link :href="`/software/${software.id}/edit`" class="text-yellow-600 hover:underline"><i
                class="las la-edit md:text-md lg:text-lg xl:text-2xl  "></i></Link>
              <button @click="deleteSoftware(software.id)" class="text-red-600 hover:underline">
                <i class="las la-trash md:text-md lg:text-lg xl:text-2xl "></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <Pagination v-if="packages?.data?.length>0" :pagination="pagination" @change-page="changePage"></Pagination>
    </div>
  </MainLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import MainLayout from '@/components/layouts/MainLayout.vue'
import { defineProps, ref, computed, watch, onMounted } from 'vue'
import Pagination from '../../components/Pagination.vue';

const props = defineProps({
  softwares: Object,
  flash: Object,
  pagination: Object,
})

const is_delete = ref(false);
const deletedId = ref(null);
const showMessage = ref(false);

function deleteSoftware(id) {
  if (confirm('Are you sure you want to delete this software?')) {
    is_delete.value = true;
    deletedId.value = id;
    router.delete(`/software/${id}`, {
      onSuccess: () => {
        console.log('deleted Successfuly');
      }
    });

  }
}

const filteredSoftware = computed(() => {
  if (is_delete.value === true && deletedId.value !== null) {
    return props.softwares.data.filter((software) => software.id != deletedId.value);
  }
  return props.softwares.data;
});

watch(() => props.flash?.success, (newVal) => {
  if (newVal) {
    showFlash();
  }
});

const showFlash = () => {
  showMessage.value = true;
  setTimeout(() => {
    showMessage.value = false;
  }, 3000);
}
onMounted(() => {
  if (props.flash?.success) {
    showFlash();
  }
});

const changePage  = (page)=>{
  router.get(route('software.index',{page},{preserveState:true}));
}
</script>
