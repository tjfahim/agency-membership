<template>
    <div>
        <MainLayout>
            <div class="max-w-md mx-auto p-6">
                <!-- Header with title and back button -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Create Software</h1>
                    <Link href="/software" class="text-gray-600 hover:text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back
                    </Link>
                </div>

                <form class="bg-white rounded-2xl shadow space-y-4 p-6"
                    @submit.prevent="submitSoftware">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" id="name" name="name" v-model="form.name"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter name" />
                        <p v-if="errors.name" class="text-red-600 text-sm mt-1">
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- URL -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                        <input type="text" id="url" name="url" v-model="form.url"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="https://example.com" />
                        <p v-if="errors.url" class="text-red-600 text-sm mt-1">
                            {{ errors.url }}
                        </p>
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="is_active" name="is_active" v-model="form.is_active"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                        <label for="is_active" class="text-sm text-gray-700 ml-2">Active</label>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit" :disabled="processing"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </MainLayout>
    </div>
</template>

<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue';

const form = useForm({
    name: '',
    url: '',
    is_active: false,
})

let errors = ref({});

function submitSoftware() {
    form.post('/software', {
        onSuccess: () => {
            form.reset();
            console.log('success');
        },
        onError: () => {
            errors.value = form.errors;
            console.log(errors.value);
        },
    })
}

const processing = form.processing
</script>