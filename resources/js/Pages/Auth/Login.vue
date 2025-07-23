<template>
    <div id="layoutAuthentication">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-sky-600 px-4">
            <div class="w-full max-w-md">
                <div class="bg-white shadow-xl rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>
                    <form @submit.prevent="login" class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                address</label>
                            <input id="email" type="email" v-model="form.email"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="you@example.com" required />
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input id="password" type="password" v-model="form.password"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="********" required />
                                 <p v-if="error" class="text-red-600 text-sm mt-1">
                        {{ error }}
                    </p>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center text-sm text-gray-600">
                                <input type="checkbox" class="mr-2" />
                                Remember me
                            </label>
                            <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
                            Login
                        </button>
                    </form>
                    <p class="mt-6 text-center text-sm text-gray-600">
                        Don't have an account?
                        <Link :href="`/register`" class="text-blue-600 hover:underline">Sign up</Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Link,useForm } from '@inertiajs/vue3';
import {ref} from 'vue';

const error = ref('');
const form = useForm({
    email: '',
    password: '',
});
const login = () => {
    form.post(route('login'),{
        onSuccess:()=>{
            form.reset();
        },
        onError:  (errors)=>{
            error.value = errors.error;
            console.log(errors);
        }
    });
}
</script>