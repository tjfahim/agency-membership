<template>
  <div v-if="serverError" class="text-red-500 p-2 my-2 bg-slate-200">
    {{ serverMessage }}
  </div>
  <div class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50" v-if="popUpModalEdit">
    <form class="bg-[#0F1551] rounded-2xl shadow space-y-4 p-6 w-1/3 pt-15 relative" @click.stop
      @submit.prevent="submitEditPayment">
      <div class="flex justify-between items-center">
        <div class="header w-full flex justify-end ">
          <h2
            class="text-3xl font-bold text-white rounded-lg shadow-md px-2 py-1 text-center mb-6 inline-block pb-2 ">
            Edit Payment
          </h2>
        </div>
        <div class="back w-1/2 flex justify-end">
          <Link href="/payment" class="!text-amber-600 !hover:text-amber-700 flex items-center">
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
        <label for="user" class="block text-base font-medium text-amber-600 ml-2 mb-2">Users</label>
        <select id="user" v-model="form.user_id"
          class="w-full px-4 py-2 border text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option disabled value="">Select User</option>
          <option v-for="(user, index) in page.props.users" :key="index" :value="user.id" class="text-black hover:!text-black">
            {{ user.name }}
          </option>
        </select>
        <p v-if="errors.user_id" class="text-red-600 text-sm mt-1">{{ errors.user_id }}</p>
      </div>

      <div>
        <label for="package" class="block text-base font-medium text-amber-600 ml-2 mb-2">Package</label>
        <select id="package" v-model="form.package_id"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 text-white focus:ring-blue-500">
          <option disabled value="">Select Package</option>
          <option v-for="(ourPackage, index) in rawPackages" :key="index" :value="ourPackage.id" class="text-black hover:!text-black">
            {{ ourPackage.name }}
          </option>
        </select>
        <p v-if="errors.package_id" class="text-red-600 text-sm mt-1">{{ errors.package_id }}</p>
      </div>

      <div>
        <label for="amount" class="block texbasemd font-bold text-amber-600 ml-2 mb-2">Amount:</label>
        <span class="text-amber-600 ml-2">{{ form.amount }} <span v-if="form.amount">tk</span></span>
        <p v-if="errors.amount" class="text-red-600 text-sm mt-1">{{ errors.amount }}</p>
      </div>

      <div>
        <label for="payment_method" class="block text-base font-medium text-amber-600 ml-2 mb-2">Payment Method</label>
        <select id="payment_method" v-model="form.payment_method"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
          <option disabled value="" selected>Select Payment</option>
          <option value="Cash" class="text-black hover:!text-black">Cash</option>
          <option value="Bank" class="text-black hover:!text-black">Bank</option>
          <option value="Card" class="text-black hover:!text-black">Card</option>
          <option value="Free" class="text-black hover:!text-black">Free</option>
        </select>
        <p v-if="errors.payment_method" class="text-red-600 text-sm mt-1">{{ errors.payment_method }}</p>
      </div>

      <div>
        <label for="payment_status" class="block text-base font-medium text-amber-600 ml-2 mb-2">Payment Status</label>
        <select id="payment_status" v-model="form.payment_status"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
          <option disabled value="">Select Status</option>
          <option value="Paid" class="text-black hover:!text-black">Paid</option>
          <option value="Due" class="text-black hover:!text-black">Due</option>
          <option value="Pending" class="text-black hover:!text-black">Pending</option>
          <option value="Unpaid" class="text-black hover:!text-black">Unpaid</option>
        </select>
        <p v-if="errors.payment_status" class="text-red-600 text-sm mt-1">{{ errors.payment_status }}</p>
      </div>
      <div class="flex items-center space-x-3 mt-2">
        <input type="checkbox" id="auto_renew" v-model="form.auto_renew" :true-value="1" :false-value="0"
          clbases="w-5 h-5 border-amber-600 ml-2 tex2-blue-600 rounded-md focus:ring-blue-500" />
        <label for="auto_renew" class="text-base font-medium text-amber-600 ml-2 pl-2">Auto Renew</label>
      </div>
      <p v-if="errors.auto_renew" class="text-red-600 text-xs mt-1">{{ errors.auto_renew }}</p>
      <!-- Submit -->
      <div>
        <button type="submit"
          class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 roubaseed-lg disabled:bg-amber-600 ml-2 dis2bled:cursor-not-allowed"
          :disabled="processing">
          Update Payment
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useForm, usePage, Link } from '@inertiajs/vue3'
import { ref, watch, defineProps, onMounted, computed } from 'vue'

const page = usePage()

const props = defineProps({
  popUpModalEdit: Boolean,
  paymentData: {
    type: Object,
  },
  users: Array,
  packages: Array,

})

const emit = defineEmits(['close'])

const form = useForm({
  user_id: props.paymentData.user_id || '',
  package_id: props.paymentData.subscription?.package?.id || '',
  payment_method: props.paymentData.payment_method || '',
  payment_status: props.paymentData.payment_status || '',
  amount: props.paymentData.amount || '',
  duration: props.paymentData.duration || '',
  duration_type: props.paymentData.duration_type || '',
  auto_renew: props.paymentData.auto_renew || 0,
})

let errors = ref({})
const serverError = ref(false)
const serverMessage = ref('')

const rawPackages = ref([])

onMounted(async () => {
  // Load packages if needed
  console.log("Auto Renew:", form.auto_renew);
  try {
    const response = await axios.get('/get_packages_payment');
    rawPackages.value = response.data;
  } catch (e) {
    console.error(e)
  }
})

watch(() => form.package_id, (newPkgId) => {
  const selectedPackage = rawPackages.value.find(pkg => pkg.id == newPkgId)
  form.amount = selectedPackage.is_free == 1 ? 0 : selectedPackage.price
  form.payment_status = selectedPackage.is_free == 1 ? 'Paid' : '';
  form.duration = selectedPackage.duration
  form.duration_type = selectedPackage.duration_type;
  form.payment_method= selectedPackage.is_free == 1 ? 'Free' :'';

})

const submitEditPayment = () => {
  form.put(`/payment/${props.paymentData.id}`, {
    onSuccess: () => {
      console.log('emit close triggered');
      form.reset()
      emit('close')
    },
    onError: () => {
      errors.value = form.errors
      console.error('Validation errors:', form.errors)
    },
  })
}

watch(() => props.paymentData, (newPayment) => {
  if (newPayment) {
    form.user_id = newPayment.user_id || ''
    form.package_id = newPayment.subscription?.package?.id || ''
    form.payment_method = newPayment.payment_method || ''
    form.payment_status = newPayment.payment_status || ''
    form.amount = newPayment.amount || ''
    form.duration = newPayment.duration || ''
    form.duration_type = newPayment.duration_type || ''
    form.auto_renew = newPayment.auto_renew || '';
    form.clearErrors()  // Clear any validation errors if needed
  }
}, { immediate: true })
const processing = form.processing
</script>
