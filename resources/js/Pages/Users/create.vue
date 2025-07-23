<script setup>
import { ref, reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import MainLayout from '../../components/layouts/MainLayout.vue';

const form = useForm({
  name: '',
  email: '',
  password: '',
  web_url: '',
  status: '',
});


// Simulate submit logic (replace with API call or Inertia post)
const submitForm = async () => {
  form.post(route('users.store'), {
    onSuccess: () => {
      form.reset();
    }
  });
}
</script>

<template>
  <MainLayout>
    <div class="max-w-md mx-auto p-6 bg-white rounded-xl shadow-md mt-10">
      <h2 class="text-2xl font-semibold mb-4">Create User</h2>
      <form @submit.prevent="submitForm" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input v-model="form.name" type="text" id="name"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" />
          <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">
            {{ form.errors.name }}
          </p>
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input v-model="form.email" type="email" id="email"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" />
          <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">
            {{ form.errors.email }}
          </p>
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input v-model="form.password" type="password" id="password"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" />
          <p v-if="form.errors.password" class="text-red-600 text-sm mt-1">
            {{ form.errors.password }}
          </p>
        </div>
        <div>
          <label for="web_url" class="block text-sm font-medium text-gray-700">URL</label>
          <input v-model="form.web_url" type="url" id="web_url"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" />
          <p v-if="form.errors.web_url" class="text-red-600 text-sm mt-1">
            {{ form.errors.web_url }}
          </p>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
          Create User
        </button>
      </form>
    </div>
  </MainLayout>
</template>
