<template>
    <div v-if="serverError" class="text-red-500 p-2 my-2 bg-slate-200">
        {{ serverMessage }}
    </div>

    <div class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50" v-if="editUserModal">
        <div class="absolute inset-0" @click="$emit('close')"></div>

        <form @submit.prevent="editUser" @click.stop
            class="bg-[#0F1551] text-white rounded-2xl shadow space-y-4 p-6 w-full max-w-md relative z-10">
                    <div class="flex justify-between items-center">
        <div class="header w-full flex justify-end ">
          <h2
            class="text-3xl font-bold text-white rounded-lg shadow-md px-2 py-1 text-center mb-6 inline-block pb-2 ">
            Edit User
          </h2>
        </div>
        <div class="back w-1/2 flex justify-end">
          <Link href="/users" class="text-gray-600 hover:text-gray-800 flex items-center">
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
                <label for="name" class="block text-base font-medium text-white mb-1">Name</label>
                <input v-model="form.name" type="text" id="name"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-base font-medium text-white mb-1">Email</label>
                <input v-model="form.email" type="email" id="email"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
            </div>

            <!-- Password -->
            <div class="relative">
                <label for="password" class="block text-base font-medium text-white mb-1">Password</label>
                <div class="flex items-center">
                    <input :type="showPassword ? 'text' : 'password'" v-model="form.password" id="password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Leave blank to keep current password" />
                    <button type="button" @click="togglePasswordVisibility"
                        class="ml-2 p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                        <span v-if="showPassword">👁️</span>
                        <span v-else>👁️‍🗨️</span>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed"
                    :disabled="form.processing">
                    Update User
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'

const emit = defineEmits(['close'])

const props = defineProps({
    User: Object,
    editUserModal: Boolean,
})

const form = useForm({
    name: props.User?.name || '',
    email: props.User?.email || '',
    password: '',
    created_at: props.User?.created_at || '',
})

const serverError = ref(false)
const serverMessage = ref('')
const showPassword = ref(false)

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value
}

const editUser = () => {
    form.put(route('update.user', props.User.id), {
        onSuccess: () => {
            form.reset('password') // Optional: reset only password
            emit('close')
        },
        onError: () => {
            serverError.value = true; 
            serverMessage.value = 'Something went wrong. Please check the form.'
            setTimeout(() => {
                serverError.value = false
                serverMessage.value = ''
            }, 2000)
        },
    })
}

watch(() => props.User, (newUser) => {
    if (newUser) {
        form.name = newUser.name || ''
        form.email = newUser.email || ''
        form.created_at = newUser.created_at || ''
        form.password = ''
        form.clearErrors()
    }
}, { immediate: true })

</script>
