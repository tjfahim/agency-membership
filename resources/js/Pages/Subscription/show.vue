<template>
  <MainLayout>
    <div class="max-w-3xl mx-auto mt-16 px-4">
      <form class="bg-white border border-gray-200 rounded-3xl shadow-lg p-10 space-y-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 text-center mb-6">Subscription Details</h1>

        <!-- User -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">User</label>
          <input type="text" id="name" v-model="subscription.user.name" disabled
            class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 cursor-not-allowed" />
        </div>

        <!-- Package -->
        <div>
          <label for="package.name" class="block text-sm font-medium text-gray-700 mb-1">Package</label>
          <input type="text" id="package.name" v-model="subscription.package.name" disabled
            class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 cursor-not-allowed" />
        </div>

        <!-- Start At -->
        <div>
          <label for="start_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
          <input type="date" id="start_at" v-model="subscription.start_at" disabled
            class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 cursor-not-allowed" />
        </div>

        <!-- End At -->
        <div>
          <label for="end_at" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
          <input type="date" id="end_at" v-model="subscription.end_at" disabled
            class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 cursor-not-allowed" />
        </div>

        <!-- Payment Status -->
        <div>
          <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
          <input type="text" id="payment_status" v-model="subscription.payment_status" disabled
            class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 cursor-not-allowed" />
        </div>

        <!-- Checkboxes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
          <!-- Auto Renew -->
          <div class="flex items-center space-x-3">
            <input type="checkbox" id="auto_renew" disabled v-model="isAutoRenewCheckbox"
              class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3" />
            <label for="auto_renew" class="text-sm font-medium text-gray-700 ml-3">Auto Renew</label>
          </div>

          <!-- Active -->
          <div class="flex items-center space-x-3">
            <input type="checkbox" id="is_active" disabled v-model="isActiveCheckbox"
              class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3" />
            <label for="is_active" class="text-sm font-medium text-gray-700  ml-3">Active</label>
          </div>
        </div>
      </form>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { defineProps, computed, onMounted } from 'vue';

const props = defineProps({
  subscription: {
    type: Object,
    required: true,
  },
});

const isActiveCheckbox = computed({
  get: () => props.subscription.is_active === 1,
  set: (val) => {
    props.subscription.is_active = val ? 1 : 0;
  },
});

const isAutoRenewCheckbox = computed({
  get: () => props.subscription.auto_renew === 1,
  set: (val) => {
    props.subscription.auto_renew = val ? 1 : 0;
  },
});

onMounted(() => {
  console.log(props.subscription);
});
</script>
