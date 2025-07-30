<template>
    <div id="layoutAuthentication">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-sky-600 px-4">
            <div class="w-full max-w-md">
                <div class="bg-white shadow-xl rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register</h2>
                    <form @submit.prevent="register" class="space-y-4">
                        <div>
                           <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Name
                                </label>
                            <input id="name" type="text" v-model="form.name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="" required />
                        </div>
                         <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                    {{form.errors.name }}
                </p>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                address</label>
                            <input id="email" type="email" v-model="form.email"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="you@example.com" required />
                        </div>
                         <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">
                    {{form.errors.email }}
                </p>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input id="password" type="password" v-model="form.password"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="********" required />
                        </div>
                         <p v-if="form.errors.password" class="text-red-600 text-sm mt-1">
                    {{form.errors.password }}
                </p>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center text-sm text-gray-600">
                                <input type="checkbox" class="mr-2" />
                                Remember me
                            </label>
                            <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
                            Register
                        </button>
                    </form>
                    <div class="card-footer text-center py-3">
                        <div class="small">Already have an account? <Link :href="`/login`">Login</Link></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Link,useForm } from '@inertiajs/vue3';
const form = useForm({
    name: '',
    email: '',
    password: '',
});
const register = () => {
    form.post(route('register'));
}
</script>