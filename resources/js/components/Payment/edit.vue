<template>
  <div v-if="serverError" class="text-red-500 p-2 my-2 bg-slate-200">
    {{ serverMessage }}
  </div>
  <div class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50" v-if="popUpModalEdit && paymentData">
    <div class="absolute inset-0" @click="$emit('close')"></div>
    <form class="!bg-white rounded-2xl shadow space-y-4 p-6 w-1/3 pt-15 relative" @click.stop
      @submit.prevent="submitEditPayment">
      <h1 class="text-center my-5">Edit Payment</h1>

      <div>
        <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Users</label>
        <select id="user" v-model="form.user_id"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option disabled value="">Select User</option>
          <option v-for="(user, index) in page.props.users" :key="index" :value="user.id">
            {{ user.name }}
          </option>
        </select>
        <p v-if="errors.user_id" class="text-red-600 text-sm mt-1">{{ errors.user_id }}</p>
      </div>

      <div>
        <label for="package" class="block text-sm font-medium text-gray-700 mb-1">Package</label>
        <select id="package" v-model="form.package_id"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option disabled value="">Select Package</option>
          <option v-for="(ourPackage, index) in page.props.packages" :key="index" :value="ourPackage.id">
            {{ ourPackage.name }}
          </option>
        </select>
        <p v-if="errors.package_id" class="text-red-600 text-sm mt-1">{{ errors.package_id }}</p>
      </div>

      <div>
        <label for="amount" class="block text-md font-bold text-gray-700 mb-1">Amount:</label>
        <span class="text-slate-700 ml-2">{{ form.amount }} <span v-if="form.amount">tk</span></span>
        <p v-if="errors.amount" class="text-red-600 text-sm mt-1">{{ errors.amount }}</p>
      </div>

      <div>
        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
        <select id="payment_method" v-model="form.payment_method"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option disabled value="" selected >Select Payment</option>
          <option value="Cash">Cash</option>
          <option value="Bank">Bank</option>
          <option value="Card">Card</option>
        </select>
        <p v-if="errors.payment_method" class="text-red-600 text-sm mt-1">{{ errors.payment_method }}</p>
      </div>

      <div>
        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
        <select id="payment_status" v-model="form.payment_status"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option disabled value="">Select Status</option>
          <option value="Paid">Paid</option>
          <option value="Due">Due</option>
          <option value="Pending">Pending</option>
          <option value="Unpaid">Unpaid</option>
        </select>
        <p v-if="errors.payment_status" class="text-red-600 text-sm mt-1">{{ errors.payment_status }}</p>
      </div>

      <!-- Submit -->
      <div>
        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed"
          :disabled="processing">
          Update Payment
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, watch, defineProps, onMounted } from 'vue'

const page = usePage()

const props = defineProps({
  popUpModalEdit: Boolean,
  paymentData: {
    type:Object,
  },
  users: Array,
  packages : Array,

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
})

let errors = ref({})
const serverError = ref(false)
const serverMessage = ref('')

const rawPackages = ref([])

onMounted(async () => {
  // Load packages if needed
  console.log(props.paymentData);
  try {
    const res = await fetch('/get_packages')
    rawPackages.value = await res.json()
  } catch (e) {
    console.error(e)
  }
})

watch(() => form.package_id, (newPkgId) => {
  const selectedPackage = rawPackages.value.find(pkg => pkg.id == newPkgId)
  if (!selectedPackage) {
    serverError.value = true
    serverMessage.value = 'No software is included in this package. Please select another package.'
    setTimeout(() => {
      serverError.value = false
      serverMessage.value = ''
    }, 3000)
    return
  }

  form.amount = selectedPackage.is_free == 1 ? 0 : selectedPackage.price
  form.duration = selectedPackage.duration
  form.duration_type = selectedPackage.duration_type
})

const submitEditPayment = () => {
  form.put(`/payment/${props.paymentData.id}`, {
    onSuccess: () => {
      emit('close')
      form.reset()
      console.log('Payment updated successfully')
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
    form.clearErrors()  // Clear any validation errors if needed
  }
}, { immediate: true })
const processing = form.processing
</script>
