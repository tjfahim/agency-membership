<template>
  <div class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50" v-if="popUpModalView && paymentData">
    <div class="absolute inset-0" @click="$emit('close')"></div>
    <div class="!bg-white rounded-2xl shadow space-y-4 p-6 w-1/3 pt-15 relative" @click.stop>
      <h1 class="text-center text-xl font-semibold">View Payment</h1>

      <div>
        <strong>User:</strong>
        <p class="text-gray-700">{{ paymentData?.user?.name }}</p>
      </div>

      <div>
        <strong>Package:</strong>
        <p class="text-gray-700">{{ paymentData?.subscription?.package?.name ?? 'N/A' }}</p>
      </div>

      <div>
        <strong>Price:</strong>
        <p class="text-gray-700">
          {{ paymentData?.subscription?.package?.is_free ? 'Free' : `${paymentData?.amount} Tk` }}
        </p>
      </div>

      <div>
        <strong>Payment Method:</strong>
        <p class="text-gray-700">{{ paymentData?.payment_method ?? 'N/A' }}</p>
      </div>

      <div>
        <strong>Status:</strong>
        <p class="text-gray-700">{{ paymentData?.payment_status ?? 'N/A' }}</p>
      </div>

      <div>
        <strong>Duration:</strong>
        <p class="text-gray-700">
          {{ paymentData?.subscription?.package?.duration }} 
          {{ paymentData?.subscription?.package?.duration_type }}
        </p>
      </div>

      <div>
        <strong>Expired At:</strong>
        <p class="text-gray-700">{{ paymentData?.subscription?.end_at_formatted ?? 'N/A' }}</p>
      </div>

      <div>
        <strong>Free Package:</strong>
        <p class="text-green-700" v-if="paymentData?.subscription?.package?.is_free === 1">Yes</p>
        <p class="text-red-700" v-else>No</p>
      </div>

      <div>
        <strong>Softwares Included:</strong>
        <ul v-if="paymentData?.subscription?.package?.softwares?.length"
            class="list-disc list-inside text-gray-700 mt-2">
          <li v-for="software in paymentData.subscription.package.softwares" :key="software.id">
            {{ software.name }}
          </li>
        </ul>
        <p v-else class="text-gray-500">No software assigned to this package.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, onMounted } from 'vue'

const props = defineProps({
  popUpModalView: Boolean,
  paymentData: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close'])

onMounted(() => {
  console.log('Viewing payment:', props.paymentData)
})
</script>
