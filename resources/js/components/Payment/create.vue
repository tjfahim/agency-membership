<template>
    <div v-if="serverError" class="text-red-500 p-2 my-2 bg-slate-200">
        {{ serverMessage }}
    </div>
    <div class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50" v-if="popUpModalCreate">
        <div class="absolute inset-0 " @click="$emit('close')"></div>
        <form class="!bg-white  rounded-2xl shadow space-y-4 p-6 w-1/3  pt-15 relative" @click.stop
            @submit.prevent="submitPayment">
            <h1 class="text-center my-5">Create Payment</h1>
            <div>
                <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Users
                </label>
                <select id="user" v-model="form.user_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option disabled value="">Select User</option>
                    <option v-for="(user, index) in page.props.users" :key="index" :value="user.id">{{ user.name }}
                    </option>
                </select>
                <p v-if="errors.user_id" class="text-red-600 text-sm mt-1">
                    {{ errors.user_id }}
                </p>
            </div>
            <div>
                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Package
                </label>
                <select id="payment_status" v-model="form.package_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option disabled value="">Select Package</option>
                    <option v-for="(ourPackage, index) in page.props.packages" :key="index" :value="ourPackage.id">{{
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
            <!--:disabled="processing || subscription.payment_status === 'Paid'"-->

            <div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Pay
                </button>
            </div>
        </form>
    </div>

</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted, watch, defineProps, toRaw } from 'vue';
import axios from 'axios';
const page = usePage();

const props = defineProps({
    flash: {
        type: Object,
    },
    popUpModalCreate: Boolean,
});
const packages = page.props.packages;
const rawPackages = ref([]);

const emit = defineEmits(['close'])

onMounted(async() => {
    if (props.flash?.error) {
        showFlash();
    }
    const response = await axios.get('/get_packages');
    rawPackages.value = response.data;
    console.log(rawPackages.value);

});

const showMessage = ref(false);

const showFlash = () => {
    showMessage.value = true;
    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
}

const form = useForm({
    user_id: '',
    package_id: "",
    payment_method: '',
    payment_status: '',
    amount: "",
    duration: "",
    duration_type: "",
});

let errors = ref({});

const submitPayment = () => {
    form.post('/payment', {
        onSuccess: () => {
            form.reset();
            console.log('success');
            emit('close');
        },
        onError: () => {
            errors.value = form.errors;
            console.log(errors.value);
        },
    })
}

const processing = form.processing;
const serverError = ref(false);
const serverMessage = ref('');
watch(() => form.package_id, (newPkgId) => {
    console.log("Selected package ID:", newPkgId);
    
    const selectedPackage = rawPackages.value.find(pkg => pkg.id == newPkgId); 

    if (!selectedPackage) {
    console.warn('Package not found for ID:', newPkgId);
    serverError.value = true;
    serverMessage.value = 'No software is included in this package. Please select softwares!';

    setTimeout(() => {
        serverError.value = false;
        serverMessage.value = '';
    }, 3000);

    return;
    }

    form.amount = selectedPackage.is_free == 1 ? 0 : selectedPackage.price;
    form.duration = selectedPackage.duration;
    form.duration_type = selectedPackage.duration_type;

    console.log('Updated form:', form);
});
</script>