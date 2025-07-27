<template>
    <div>
        <MainLayout>
            <form class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow space-y-4 mt-10"
                @submit.prevent="updatePayment">
                <h1 class="text-center my-5">Edit Payment</h1>
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Users
                    </label>
                    <select id="user" v-model="form.user_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option disabled value="">Select User</option>
                        <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.name }}</option>
                    </select>
                    <p v-if="errors.user_id" class="text-red-600 text-sm mt-1">
                        {{ errors.user_id }}
                    </p>
                </div>
                <div>
                    <label for="package" class="block text-sm font-medium text-gray-700 mb-1">Package
                    </label>
                    <select id="package" v-model="form.package_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option disabled value="">Select Package</option>
                        <option v-for="(ourPackage, index) in packages" :key="index" :value="ourPackage.id" @click="alert(1)">{{
                            ourPackage.name }}</option>
                    </select>
                    <p v-if="errors.package_id" class="text-red-600 text-sm mt-1">
                        {{ errors.package_id }}
                    </p>
                </div>
                <div>
                    <label for="amount" class=" block text-md font-bold text-gray-700 mb-1">Amount:</label>
                    <span class="text-slate-700 ml-2">{{ form.amount }} <span v-if="form.amount">tk</span></span>
                    <p v-if="errors.amount" class="text-red-600 text-sm mt-1">
                        {{ errors.amount }}
                    </p>
                </div>
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment
                        Method</label>
                    <select id="user" v-model="form.payment_method"
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
                    <select id="payment_status" v-model="form.payment_status"
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
                <!-- Submit -->
                <!-- || subscription.payment_status === 'Paid'"-->
                <div>
                    <button type="submit" :disabled="processing"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Pay
                    </button>
                </div>
            </form>
        </MainLayout>
    </div>
</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted, defineProps, watch } from 'vue';

const props = defineProps({
    payment: {
        type: Array,
        required: true,
    },
    users: {
        type: Array,
        default: () => []
    },
    packages: {
        type: Array,
        default: () => []
    }
})
const form = useForm({
    user_id: props.payment.user_id || '',
    package_id: props.payment.subscription.package.id || "",
    amount: props.payment.amount || '',
    payment_method: props.payment.payment_method || '',
    payment_status: props.payment.payment_status || '',
    duration: props.payment.subscription.package.duration || "",
    duration_type: props.payment.subscription.package.duration_type ||"",
});

onMounted(() => {
    console.log(props.payment);
});
let errors = ref({});
const is_free = ref(false);
function updatePayment() {
    form.put(route('payment.update', props.payment.id), {
        onSuccess: () => {
            form.reset();
            console.log('success');
        },
        onError: () => {
            errors.value = form.errors;
            console.log(errors.value);
        },
    });
}

const processing = form.processing;
watch(() => form.package_id, (newPkgId) => {
    const selectedPackage = props.packages.find((pkg) => pkg.id === newPkgId);
    selectedPackage.is_free === 1 ? form.amount = 0 : form.amount = selectedPackage.price; //checking if the price is free or not

    form.duration = selectedPackage.duration;
    form.duration_type = selectedPackage.duration_type;
})
</script>