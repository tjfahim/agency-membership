<template>
    <MainLayout>
        <div v-if="showMessage" class="alert alert-success">
            {{ flash?.success }}
        </div>
         <div v-if="showError" class="alert alert-danger">
            {{ flash?.error }}
        </div>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Payment List</h1>

            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">User</th>
                        <th class="border px-4 py-2 text-left">Amount</th>
                        <th class="border px-4 py-2 text-left">Payment Method</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="payment in filteredPayment" :key="payment.id" class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ payment.user.name }}</td>
                        <td class="border px-4 py-2">
                            {{ payment.amount }} tk
                        </td>
                        <td class="border px-4 py-2">
                            {{ payment.payment_method }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ payment.payment_status }} 
                        </td>
                        <td class="flex justify-center items-center border md:px-4 md:py-2 text-center space-x-2 md:space-x-2 xl:space-x-4">
                            <Link :href="`/payment/${payment.id}`" class="text-blue-600 hover:underline"><i
                                class="las la-eye md:text-md lg:text-lg xl:text-2xl  text-green-500"></i></Link>
                            <Link :href="`/payment/${payment.id}/edit`" class="text-yellow-600 hover:underline"><i
                                class="las la-edit md:text-md lg:text-lg xl:text-2xl  "></i></Link>
                            <button @click="deletePayment(payment.id)" class="text-red-600 hover:underline">
                                <i class="las la-trash md:text-md lg:text-lg xl:text-2xl "></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination v-if="payments.data.length>0" :pagination="pagination" @change-page="changePage"></Pagination>
        </div>
    </MainLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import MainLayout from '@/components/layouts/MainLayout.vue'
import Pagination from '../../components/Pagination.vue'
import { defineProps, ref, computed, onMounted, watch } from 'vue'

const props = defineProps({
    payments: Array,
    flash: Object,
    pagination:Object,
})

const is_delete = ref(false);
const deletedId = ref(null);
const showMessage = ref(false);

function deletePayment(id) {
    if (confirm('Are you sure you want to delete this payment?')) {
        is_delete.value = true;
        deletedId.value = id;
        router.delete(`/payment/${id}`, {
            onSuccess: () => {
                console.log('deleted Successfuly');
            }
        });

    }
}

const filteredPayment = computed(() => {
    if (is_delete.value === true && deletedId.value !== null) {
        return props.payments.data.filter((payment) => payment.id != deletedId.value);
    }
    return props.payments.data;
});
const showError = ref(false);

onMounted(() => {
    if (props.flash?.success) {
        showFlash();
    }
    if(props.flash?.error){
        showFlashError();
    }
    console.log(props.payments);
});

const showFlash = () => {
    showMessage.value = true;
    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
}

const showFlashError = ()=>{
    showError.value = true;
    setTimeout(()=>{
        showError.value = false;
    },3000);
}
watch(() => props.flash?.success, (newVal) => {
    if (newVal) {
        showFlash();
    }
});
const changePage = (page)=>{
    router.get(route('payment.index',{page},{preserveState:true}));
}

</script>
