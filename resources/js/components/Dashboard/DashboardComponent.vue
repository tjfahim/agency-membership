<template>
  <div class="p-4">
   
    <!-- Summary Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Total Users Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Users</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ summary.total_users }}</p>
          </div>
          <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <i class="fas fa-users text-xl"></i>
          </div>
        </div>
      </div>

      <!-- Total Payments Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Payments</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ summary.total_payments }} tk</p>
          </div>
          <div class="p-3 rounded-full bg-green-100 text-green-600">
            <i class="fas fa-money-bill-wave text-xl"></i>
          </div>
        </div>
      </div>

      <!-- Due Payments Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Due Payments</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ summary.due_payments }} tk</p>
          </div>
          <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
            <i class="fas fa-exclamation-circle text-xl"></i>
          </div>
        </div>
      </div>

      <!-- Pending Payments Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Pending Payments</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ summary.pending_payments }} tk</p>
          </div>
          <div class="p-3 rounded-full bg-orange-100 text-orange-600">
            <i class="fas fa-clock text-xl"></i>
          </div>
        </div>
      </div>

      <!-- Total Software Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Software</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ summary.total_softwares }}</p>
          </div>
          <div class="p-3 rounded-full bg-purple-100 text-purple-600">
            <i class="fas fa-code text-xl"></i>
          </div>
        </div>
      </div>

      <!-- Total Packages Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Packages</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ summary.total_packages }}</p>
          </div>
          <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
            <i class="fas fa-box-open text-xl"></i>
          </div>
        </div>
      </div>

      <!-- Active Subscriptions Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Active Subscriptions</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ summary.active_subscriptions }}</p>
          </div>
          <div class="p-3 rounded-full bg-teal-100 text-teal-600">
            <i class="fas fa-sync-alt text-xl"></i>
          </div>
        </div>
      </div>

      <!-- Expired Subscriptions Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Expired Subscriptions</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ summary.expired_subscriptions }}</p>
          </div>
          <div class="p-3 rounded-full bg-red-100 text-red-600">
            <i class="fas fa-calendar-times text-xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">Recent Payments</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead class="bg-gray-100">
            <tr class="text-left">
              <th class="px-4 py-2">Username</th>
              <th class="px-4 py-2">Package</th>
              <th class="px-4 py-2">Payment Date</th>
              <th class="px-4 py-2">Amount</th>
              <th class="px-4 py-2">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(payment, index) in recentPayments" :key="index" class="hover:bg-gray-50 border-b">
              <td class="px-4 py-2">{{ payment.subscription?.user?.name }}</td>
              <td class="px-4 py-2">{{ payment.subscription?.package?.name }}</td>
              <td class="px-4 py-2">{{ payment.created_at_format }}</td>
              <td class="px-4 py-2">{{ payment.amount }} tk</td>
              <td class="px-4 py-2">
                <span :class="{
                  'text-green-600': payment.subscription?.payment_status === 'Paid',
                  'text-red-500': payment.subscription?.payment_status === 'Due',
                  'text-yellow-500': payment.subscription?.payment_status === 'Pending'
                }">
                  {{ payment.subscription?.payment_status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps({
  summary: {
    type: Object,
    required: true,
    default: () => ({
      total_users: 0,
      total_payments: 0,
      due_payments: 0,
      pending_payments: 0,
      total_softwares: 0,
      total_packages: 0,
      active_subscriptions: 0,
      expired_subscriptions: 0
    })
  },
  recentPayments: {
    type: Array,
    required: true,
    default: () => []
  },
  users: {
    type: Object,
    required: false
  },
  pagination: {
    type: Object,
    required: false
  }
});
</script>

<style>
.btnRadius {
  border-radius: 10px;
}
</style>