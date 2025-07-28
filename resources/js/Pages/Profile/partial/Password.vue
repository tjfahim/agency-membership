<!-- resources/js/Pages/Settings/Partials/PasswordSettings.vue -->
<template>
  <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h3 class="text-2xl font-semibold text-[#0F1551] mb-6">Change Password</h3>

    <form @submit.prevent="changePassword" class="space-y-5">
      <!-- Current Password -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
        <input
          v-model="form.current_password"
          type="password"
          placeholder="Enter current password"
          class="input"
        />
        <span v-if="form.errors.current_password" class="text-red-500 text-sm">{{ form.errors.current_password }}</span>
      </div>

      <!-- New Password -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
        <input
          v-model="form.password"
          type="password"
          placeholder="Enter new password"
          class="input"
        />
        <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          placeholder="Confirm new password"
          class="input"
        />
        <span v-if="form.errors.password_confirmation" class="text-red-500 text-sm">{{ form.errors.password_confirmation }}</span>
      </div>

      <button type="submit" class="input bg-[#0F1551]  text-white btn-primary w-full mt-4">
        Update Password
      </button>
    </form>
  </div>
</template>


<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const changePassword = () => {
  console.log(form);
  form.post(route('password.update'));
};
</script>

