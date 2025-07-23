<template>
    <div>
        <MainLayout>
            <form class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow space-y-4 mt-10"
               >
                <h1 class="text-center my-5">Payment</h1>
                <div>
                    <label for="user" class=" block text-md font-bold text-gray-700 mb-1">User Name: 
                    </label> <span class="text-slate-700 ml-2">{{ payment.user.name }}</span>
                </div>
                <div>
                    <label for="package" class=" block text-md font-bold text-gray-700 mb-1">Package Name: 
                    </label> <span class="text-slate-700 ml-2">{{ payment.subscription.package.name }}</span>
                </div>
                <div>
                    <label for="amount" class="block text-sm font-bold text-gray-700 mb-1">Amount:</label>
                      <span class="text-slate-700 ml-2">{{ payment.amount }} tk</span>
                </div>
                <div>
                    <label for="duration" class="block text-sm font-bold text-gray-700 mb-1">Package Duration:</label>
                     <span class="text-slate-700 ml-2 font-semibold">{{ payment.subscription.start_at }} to {{ payment.subscription.end_at }}</span>
                </div>
                <div class="flex flex-wrap gap-2 my-4">
                    <label for="softwares" class="block text-sm font-bold text-gray-700 mb-1">Softwares:</label>
                    <span v-for="software in payment.subscription.package.softwares" :key="software.id"
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition duration-200">
                        {{ software.name }}
                    </span>
                </div>
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select id="user" v-model="form.payment_method" disabled
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option disabled value="" selected>Select Payment</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank">Bank</option>
                        <option value="Card">Card</option>
                    </select>
                    <p v-if="errors.payment_method" class="text-red-600 text-sm mt-1">
                        {{ errors.payment_method }}
                    </p>
                </div>
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status
                    </label>
                    <select id="payment_status" v-model="form.payment_status" disabled
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option disabled value="">Select Status</option>
                        <option value="Paid">Paid</option>
                        <option value="Due">Due</option>
                        <option value="Pending">Pending</option>                    
                        <option value="Unpaid">Unpaid</option>
                    </select>
                    <p v-if="errors.payment_status" class="text-red-600 text-sm mt-1">
                        {{ errors.payment_status }}
                    </p>
                </div>

            </form>
        </MainLayout>
    </div>
</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted, defineProps } from 'vue';

const props = defineProps({
    payment:{
        type:Array,
        required:true,
    }
})
const form = useForm({
    user_id: props.payment.user_id || '',
    amount: props.payment.amount || '',
    payment_method: props.payment.payment_method || '',
    payment_status: props.payment.payment_status || '',
});


const users = ref([]);
const packages = ref([]);

let errors = ref({});

const processing = form.processing

</script>