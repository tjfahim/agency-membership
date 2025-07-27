<template>
  <MainLayout>
    <div v-if="showMessage" class="alert alert-success">
      {{ flash?.success }}
    </div>
    <div v-if="showEmpty" class="alert alert-success">
      {{ flash?.software_empty }}
    </div>
    <div :class="['p-6 relative', popupModal ? 'bg-slate-300 opacity-85' : '']">

      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Software List</h1>
        <Button @click="toggleModal"
          class="block px-4 py-2 ml-5 !text-slate-200 !no-underline rounded bg-blue-600 hover:bg-gray-700 text-lg">
          Create Software
        </Button>
      </div>
      <!--Create software pop up-->
        <createSoftwareComponent :popUpModalCreate="popUpModalCreate" @close="popUpModalCreate=false"></createSoftwareComponent>
      <!--End create software pop up-->
        <!--Edit software pop up-->
        <editSoftwareComponent :popUpModalUpdate="popUpModalUpdate" @close="popUpModalUpdate=false" :updateForm="updateForm"></editSoftwareComponent>

      <!--Edit Software pop up-->
      <!--view modal software-->
        <viewSoftwareComponent :popUpModalView="popUpModalView" @close="popUpModalView=false" :updateForm="updateForm"></viewSoftwareComponent>
      <!--End view modal software-->
      <table class="w-full table-auto border-collapse border border-gray-300 overflow-x-auto">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-4 py-2 text-left">Name</th>
            <th class="border px-4 py-2 text-left">URL</th>
            <th class="border px-4 py-2 text-left">Active</th>
            <th class="border px-4 py-2 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="software in filteredSoftware" :key="software.id" class="hover:bg-gray-50 ">
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
            <td
              class="flex justify-center items-center border md:px-4 md:py-2  text-center space-x-2 md:space-x-2 xl:space-x-4">
              <Button @click="viewSoftware(software)" class="text-blue-600 hover:underline"><i
                class="las la-eye md:text-md lg:text-lg xl:text-2xl  text-green-500 mr-3"></i></Button>
              <Button @click="editSoftware(software)" class="text-yellow-600 hover:underline"><i
                class="las la-edit md:text-md lg:text-lg xl:text-2xl  mr-3"></i></Button>
              <button @click="deleteSoftware(software.id)" class="text-red-600 hover:underline">
                <i class="las la-trash md:text-md lg:text-lg xl:text-2xl "></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <Pagination v-if="packages?.data?.length > 0" :pagination="pagination" @change-page="changePage"></Pagination>
    </div>
  </MainLayout>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3'
import MainLayout from '@/components/layouts/MainLayout.vue'
import { defineProps, ref, computed, watch, onMounted } from 'vue'
import Pagination from '../../components/Pagination.vue';
import createSoftwareComponent from '../../components/Software/create.vue';
import editSoftwareComponent from '../../components/Software/edit.vue';
import viewSoftwareComponent from '../../components/Software/view.vue';
const form = useForm({
  name: '',
  url: '',
  is_active: true,
})

let errors = ref({});

const popUpModalCreate = ref(false);
const popUpModalUpdate = ref(false);
const popUpModalView = ref(false);

const toggleModal = () => {
  popUpModalCreate.value = !popUpModalCreate.value;
}


const updateForm = useForm({
    id:'',
    name:  '',
    url:  '',
    is_active:  true,
})
const viewSoftware = (software) =>{
  popUpModalView.value = true;
  updateForm.id = software.id;
  updateForm.name = software.name;
  updateForm.url = software.url;
  updateForm.is_active = !!software.is_active;
}
const editSoftware =  (software)=>{
  popUpModalUpdate.value = true; 
  updateForm.id = software.id;
  updateForm.name = software.name;
  updateForm.url = software.url;
  updateForm.is_active = !!software.is_active;

}


const props = defineProps({
  softwares: Object,
  flash: Object,
  pagination: Object,
})

const is_delete = ref(false);
const deletedId = ref(null);
const showMessage = ref(false);
const showEmpty = ref(false);
const emptyMessage = ref('');
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
watch(()=>props.flash?.software_empty,(newVal)=>{
   showEmpty.value = true;
   emptyMessage.value = newVal;
   setTimeout(()=>{
    showEmpty.value = false;
   },3000)
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

const changePage = (page) => {
  router.get(route('software.index', { page }, { preserveState: true }));
}
</script>
