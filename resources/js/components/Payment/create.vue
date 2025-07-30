<template>
    <div v-if="serverError" class="text-red-500 p-2 my-2 bg-slate-200">
        {{ serverMessage }}
    </div>
    <div class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50" v-if="popUpModalCreate">
        <div class="absolute inset-0 " @click="$emit('close')"></div>
        <form class="bg-[#0F1551]  rounded-2xl shadow space-y-4 p-6 w-1/3  pt-15 relative" @click.stop
            @submit.prevent="submitPayment">
               <div class="flex justify-between items-center">
        <div class="header w-full flex justify-end ">
          <h2
            class="text-3xl font-bold text-white rounded-lg shadow-md px-2 py-1 text-center mb-6 inline-block pb-2 ">
            Create Payment
          </h2>
        </div>
        <div class="back w-1/2 flex justify-end">
          <Link href="/payment" class="!text-amber-600 hover:text-amber-800 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
              clip-rule="evenodd" />
          </svg>
          Back
          </Link>
        </div>
      </div>
            <div>
                <label for="user" class="block text-base ml-2 mb-2 font-medium text-amber-600">Users
                </label>
                <select id="user" v-model="form.user_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none text-white focus:ring-2 focus:ring-blue-500">
                    <option disabled value="">Select User</option>
                    <option v-for="(user, index) in page.props.users" :key="index" :value="user.id" class="text-black hover:text-black">{{ user.name }}
                    </option>
                </select>
                <p v-if="errors.user_id" class="text-red-600 text-sm mt-1">
                    {{ errors.user_id }}
                </p>
            </div>
            <div>
                <label for="payment_status" class="block text-base ml-2 mb-2 font-medium text-amber-600 ">Package
                </label>
                <select id="payment_status" v-model="form.package_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none text-white focus:ring-2 focus:ring-blue-500">
                    <option disabled value="">Select Package</option>
                    <option v-for="(ourPackage, index) in rawPackages" :key="index" :value="ourPackage.id" class="text-black hover:text-black">{{
                        ourPackage.name }}</option>
                </select>
                <p v-if="errors.package_id" class="text-red-600 text-sm mt-1">
                    {{ errors.package_id }}
                </p>
            </div>
            <div>
                <label for="amount" class=" block texbase ml-2 mb-2md font-bold text-amber-600">Amount:</label>
                <span class=" ml-2 text-white">{{ form.amount }} <span v-if="form.amount">tk</span></span>
                <p v-if="errors.amount" class="text-red-600 text-sm mt-1">
                    {{ errors.amount }}
                </p>
            </div>
            <div>
                <label for="payment_method" class="block text-base ml-2 mb-2 font-medium text-amber-600">Payment
                    Method</label>
                <select id="user" v-model="form.payment_method"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 text-white focus:ring-blue-500">
                    <option disabled value="" selected>Select Payment</option>
                    <option value="Cash" class="text-black hover:text-black">Cash</option>
                    <option value="Bank" class="text-black hover:text-black">Bank</option>
                    <option value="Card" class="text-black hover:text-black">Card</option>
                    <option value="Free" class="text-black hover:text-black">Free</option>

                </select>
                <p v-if="errors.payment_method" class="text-red-600 text-sm mt-1">
                    {{ errors.payment_method }}
                </p>
            </div>
            <div>
                <label for="payment_status" class="block text-base ml-2 mb-2 font-medium text-amber-600">Payment Status
                </label>
                <select id="payment_status" v-model="form.payment_status"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 text-white focus:ring-blue-500">
                    <option disabled value="">Select Status</option>
                    <option value="Paid" class="text-black hover:text-black">Paid</option>
                    <option value="Due" class="text-black hover:text-black">Due</option>
                    <option value="Pending" class="text-black hover:text-black">Pending</option>
                    <option value="Unpaid" class="text-black hover:text-black">Unpaid</option>
                </select>
                <p v-if="errors.payment_status" class="text-red-600 text-sm mt-1">
                    {{ errors.payment_status }}
                </p>
            </div>
            <div class="flex items-center space-x-3 mt-2">
                <input type="checkbox" id="auto_renew" v-model="form.auto_renew"
                    class="w-5 h-5 border-gray-300 text-blue-600 rounded-md focus:ring-blue-500" :true-value="1"
                    :false-value="0" />
                <label for="auto_renew" class="text-base ml-2 mb-2 font-medium text-amber-600 pl-1">Auto Renew</label>
            </div>
            <p v-if="errors.auto_renew" class="text-red-600 text-xs mt-1">{{ errors.auto_renew }}</p>
            <!-- Submit -->
            <!--:disabled="processing || subscription.payment_status === 'Paid'"-->

            <div>
                <button type="submit"
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Pay
                </button>
            </div>
        </form>
    </div>

</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { useForm, usePage, Link, router } from '@inertiajs/vue3'
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

onMounted(async () => {
    if (props.flash?.error) {
        showFlash();
    }
    try {
        const response = await axios.get('/get_packages_payment');
        rawPackages.value = response.data;
        console.log(rawPackages.value);
    } catch (error) {
        console.error('failed to fetch packages', error);
    }

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
    auto_renew: 0,
});

let errors = ref({});

const submitPayment = () => {
    form.post('/payment', {
        onSuccess: () => {
            form.reset();
            console.log('success');
            emit('close');
            router.visit(route('payment.index'));
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
    form.amount = selectedPackage.is_free == 1 ? 0 : selectedPackage.price;
    form.payment_status = selectedPackage.is_free == 1 ? 'Paid' : '';
    form.duration = selectedPackage.duration;
    form.duration_type = selectedPackage.duration_type;
    form.payment_method= selectedPackage.is_free == 1 ? 'Free' :'';
    console.log('Updated form:', form);
});
</script>