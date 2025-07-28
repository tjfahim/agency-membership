<template>
  <!-- Flash message (optional) -->
  <div v-if="showMessage" class="text-red-500 p-2 my-2 bg-slate-200 rounded">
    {{ flash?.error || flash?.message }}
  </div>

  <!-- Modal Overlay -->
  <div class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50" v-if="createUserModal">
    <!-- Background click to close -->
    <div class="absolute inset-0" @click="$emit('close')"></div>

    <!-- Modal Box -->
    <form
      class="relative w-1/3 bg-[#0F1551]  rounded-2xl shadow-xl space-y-4 p-6"
      @click.stop
      @submit.prevent="submitForm"
    >
       <div class="flex justify-between items-center">
        <div class="header w-full flex justify-end ">
          <h2
            class="text-3xl font-bold text-white rounded-lg shadow-md px-2 py-1 text-center mb-6 inline-block pb-2 ">
            Create User
          </h2>
        </div>
        <div class="back w-1/2 flex justify-end">
          <Link href="/users" class="!text-amber-600 hover:text-gray-800 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
              clip-rule="evenodd" />
          </svg>
          Back
          </Link>
        </div>
      </div>

      <!-- Name -->
      <div>
        <label for="name" class="block text-base font-medium text-white">Name</label>
        <input
          v-model="form.name"
          type="text"
          id="name"
          class="mt-1 block text-white w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
        />
        <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">
          {{ form.errors.name }}
        </p>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-base font-medium text-white">Email</label>
        <input
          v-model="form.email"
          type="email"
          id="email"
          class="mt-1 block text-white w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
        />
        <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">
          {{ form.errors.email }}
        </p>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-base font-medium text-white">Password</label>
        <input
          v-model="form.password"
          type="password"
          id="password"
          class="mt-1 block w-full text-white border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
        />
        <p v-if="form.errors.password" class="text-red-600 text-sm mt-1">
          {{ form.errors.password }}
        </p>
      </div>

      <!-- URL -->
      <div>
        <label for="web_url" class="block text-base font-medium text-white">Website URL</label>
        <input
          v-model="form.web_url"
          type="url"
          id="web_url"
          class="mt-1 block w-full text-white border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
        />
        <p v-if="form.errors.web_url" class="text-red-600 text-sm mt-1">
          {{ form.errors.web_url }}
        </p>
      </div>

      <!-- Submit -->
      <button
        type="submit"
        class="w-full bg-amber-600 text-white py-2 rounded hover:bg-amber-700 transition disabled:bg-gray-400"
        :disabled="form.processing"
      >
        Create User
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, defineEmits, defineProps } from 'vue'
import { useForm,Link} from '@inertiajs/vue3'

const emit = defineEmits(['close'])

const props = defineProps({
  createUserModal: Boolean,
  flash: Object,
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  web_url: '',
  status: '',
})

const showMessage = ref(false)

const submitForm = async () => {
  form.post(route('users.store'), {
    onSuccess: () => {
      showMessage.value = true
      form.reset()
      emit('close')
    },
    onError: () => {
      showMessage.value = true
    },
  })
}
</script>
