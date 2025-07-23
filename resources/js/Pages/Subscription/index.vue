<template>
  <MainLayout>
    <div v-if="showMessage" class="alert alert-success">
       {{ flash?.success }}
    </div>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Subscription List</h1>

      <table class="w-full table-auto border-collapse border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-4 py-2 text-left">User</th>
            <th class="border px-4 py-2 text-left">Package</th>
            <th class="border px-4 py-2 text-left">Start date</th>
            <th class="border px-4 py-2 text-left">End date</th>
            <th class="border px-4 py-2 text-left">Payment Status</th>
            <th class="border px-4 py-2 text-left">Assigned By</th>
            <th class="border px-4 py-2 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="subscription in filteredPackage" :key="subscription.id" class="hover:bg-gray-50">
            <td class="border px-4 py-2">{{ subscription.user.name }}</td>
            <td class="border px-4 py-2">
                {{ subscription.package.name }}
            </td>
             <td class="border px-4 py-2">
                {{ subscription.start_at }}
            </td>
            <td class="border px-4 py-2">
                {{ subscription.end_at }} 
            </td>
            <td class='border px-4 py-2 text-white'>
                <span :class="['px-2 py-1 rounded',subscription.payment_status === 'Paid' ? 'bg-green-500' : 'bg-red-500']">{{ subscription.payment_status }} </span>
            </td>
              <td class="border px-4 py-2">
                {{ subscription.assigned_by.name }} 
            </td>
            <td class="border md:px-4 md:py-2  text-center space-x-2 md:space-x-2 xl:space-x-4 flex justify-center items-center">
              <Link :href="`/subscription/${subscription.id}`" class="text-blue-600 hover:underline"><i class="las la-eye md:text-md lg:text-lg xl:text-2xl  text-green-500"></i></Link>
              <Link :href="`/subscription/${subscription.id}/edit`" class="text-yellow-600 hover:underline"><i class="las la-edit md:text-md lg:text-lg xl:text-2xl  "></i></Link>
              <!-- <Link :href="`/subscription/payment/${subscription.id}`" class="text-yellow-600 hover:underline"><i class="lab la-paypal md:text-md lg:text-lg xl:text-2xl  "></i></Link> -->
              <button
                @click="deletePackage(subscription.id)"
                class="text-red-600 hover:underline"
              >
                <i class="las la-trash md:text-md lg:text-lg xl:text-2xl "></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <Pagination v-if="subscriptions?.data?.length>0" :pagination="pagination" @change-page="changePage" ></Pagination>
    </div>
  </MainLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import MainLayout from '@/components/layouts/MainLayout.vue'
import { defineProps, ref, computed, onMounted, watch} from 'vue'
import Pagination from '../../components/Pagination.vue'
const props = defineProps({
  subscriptions: Object,
  flash:Object,
  pagination:Object,
})

const is_delete = ref(false);
const deletedId = ref(null);
const showMessage = ref(false);

function deletePackage(id) {
  if (confirm('Are you sure you want to delete this subscription?')) {
    is_delete.value = true;
    deletedId.value = id;
    router.delete(`/subscription/${id}`,{
      onSuccess: ()=>{
        console.log('deleted Successfuly');
      }
    });

  }
}

const filteredPackage = computed(()=>{
  if(is_delete.value === true && deletedId.value !== null){
    return props.subscriptions.data.filter((subscription)=> subscription.id != deletedId.value);
  }
    return props.subscriptions.data;
});

onMounted(() => {
    if (props.flash?.success) {
        showFlash();
    }
    console.log(props.subscriptions);
});

const showFlash = ()=>{
    showMessage.value = true;
    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
}

watch(()=> props.flash?.success, (newVal)=>{
    if(newVal){
        showFlash();
    }
});
const changePage  = (page)=>{
  router.get(route('subscription.index',{page},{preserveState:true}));
}
</script>
