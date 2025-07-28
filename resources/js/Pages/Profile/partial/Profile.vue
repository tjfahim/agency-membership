<template>
  <div class="max-w-xl mx-auto bg-white p-4 rounded-lg shadow-lg">
    <h2 class="text-3xl font-semibold mb-6 text-[#0F1551]">Edit Profile</h2>

    <form @submit.prevent="submitProfile" class="space-y-6">
      <!-- Avatar Upload -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Avatar</label>
        <input
          type="file"
          @change="handleAvatarChange"
          accept="image/*"
          class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-sm file:font-semibold
            file:bg-[#0F1551] file:text-white
            hover:file:bg-[#2245D2]"
        />
        <div v-if="previewAvatar" class="mt-4">
          <img :src="previewAvatar" alt="Avatar Preview" class="w-24 h-24 rounded-full object-cover" />
        </div>
                <span v-if="form.errors.avatar" class="text-red-500">{{form.errors.avatar}}</span>
      </div>

      <!-- Phone -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Phone</label>
        <input
          v-model="form.phone"
          type="tel"
           placeholder="+880-1555-6666"
          class="input"
        />
        <span v-if="form.errors.phone" class="text-red-500">{{form.errors.phone}}</span>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="you@example.com"
          class="input"
        />
                <span v-if="form.errors.emails" class="text-red-500">{{form.errors.emails}}</span>

      </div>

      <!-- Address -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Address</label>
        <input
          v-model="form.address"
          type="text"
          placeholder="Dhaka"
          class="input"
        />
                <span v-if="form.errors.address" class="text-red-500">{{form.errors.address}}</span>

      </div>

      <!-- Date of Birth -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
        <input
          v-model="form.dob"
          type="date"
          class="input"
        />
                <span v-if="form.errors.dob" class="text-red-500">{{form.errors.dob}}</span>
      </div>



      <button
        type="submit"
        class="w-full bg-[#0F1551] text-white py-3 rounded-lg font-semibold hover:bg-[#2245D2] transition"
      >
        Save Changes
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

 const props = defineProps({
    profile:Object,
});
const form = useForm({
  avatar: null,
  phone: props.profile.phone || '',
  email: props.profile.email || '',
  address: props.profile.address || '',
  dob: props.profile.dob || '',
})


const previewAvatar = ref(null)

function handleAvatarChange(event) {
  const file = event.target.files[0]
  form.avatar = file

  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      previewAvatar.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

function submitProfile() {
  console.log(form);
  form.post(route('profile.update'));
}
</script>

<style>
.input {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #cbd5e1; 
  border-radius: 0.375rem; 
  font-size: 1rem;
  color: #1e293b; 
  transition: border-color 0.2s;
}
.input:focus {
  outline: none;
  border-color: #3b82f6; 
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}
</style>