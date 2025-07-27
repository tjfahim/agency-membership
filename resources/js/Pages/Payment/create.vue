<template>
  <MainLayout>
    <div class="max-w-5xl mx-auto px-6 py-12">
      <!-- Flash Error -->
      <div v-if="errors.softwares" class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        {{ errors.softwares }}
      </div>

      <div class="bg-white shadow-xl rounded-2xl p-10">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Create Payment</h1>

        <form @submit.prevent="submitPayment" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User -->
            <div>
              <label for="user" class="block text-sm font-semibold text-gray-700 mb-1">User</label>
              <select id="user" v-model="form.user_id"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option disabled value="">Select User</option>
                <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.name }}</option>
              </select>
              <p v-if="errors.user_id" class="text-red-600 text-sm mt-1">{{ errors.user_id }}</p>
            </div>

            <!-- Package -->
            <div>
              <label for="package" class="block text-sm font-semibold text-gray-700 mb-1">Package</label>
              <select id="package" v-model="form.package_id"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option disabled value="">Select Package</option>
                <option v-for="(ourPackage, index) in packages" :key="index" :value="ourPackage.id">
                  {{ ourPackage.name }}
                </option>
              </select>
              <p v-if="errors.package_id" class="text-red-600 text-sm mt-1">{{ errors.package_id }}</p>
            </div>

            <!-- Amount -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">  <div class="text-lg text-slate-800">
                Amount: {{ form.amount }} <span v-if="form.amount">tk</span>
              </div></label>
            
              <p v-if="errors.amount" class="text-red-600 text-sm mt-1">{{ errors.amount }}</p>
            </div>

            <!-- Payment Method -->
            <div>
              <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-1">Payment Method</label>
              <select id="payment_method" v-model="form.payment_method"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option disabled value="" selected>Select Payment</option>
                <option value="Cash">Cash</option>
                <option value="Bank">Bank</option>
                <option value="Card">Card</option>
              </select>
              <p v-if="errors.payment_method" class="text-red-600 text-sm mt-1">{{ errors.payment_method }}</p>
            </div>

            <!-- Payment Status -->
            <div>
              <label for="payment_status" class="block text-sm font-semibold text-gray-700 mb-1">Payment Status</label>
              <select id="payment_status" v-model="form.payment_status"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option disabled value="">Select Status</option>
                <option value="Paid">Paid</option>
                <option value="Due">Due</option>
                <option value="Pending">Pending</option>
                <option value="Unpaid">Unpaid</option>
              </select>
              <p v-if="errors.payment_status" class="text-red-600 text-sm mt-1">{{ errors.payment_status }}</p>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="mt-8">
            <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition disabled:bg-gray-400 disabled:cursor-not-allowed"
              :disabled="processing">
              Pay
            </button>
          </div>
        </form>
      </div>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted, watch, defineProps } from 'vue';


const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    packages: {
        type: Array,
        required: true,
    },
    flash: {
        type: Object,
    }
});

onMounted(() => {
    if (props.flash?.error) {
        showFlash();
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
});

let errors = ref({});
const is_free = ref(false);
const submitPayment = () => {
    form.post('/payment', {
        onSuccess: () => {
            form.reset();
            console.log('success');
        },
        onError: () => {
            errors.value = form.errors;
            console.log(errors.value);
        },
    })
}

const processing = form.processing;

watch(() => form.package_id, (newPkgId) => {
    const selectedPackage = props.packages.find((pkg) => pkg.id === newPkgId);
    selectedPackage.is_free === 1 ? form.amount = 0 : form.amount = selectedPackage.price; //checking if the price is free or not
    console.log(form.amount);
    form.duration = selectedPackage.duration;
    form.duration_type = selectedPackage.duration_type;
})
</script>