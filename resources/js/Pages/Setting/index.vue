<template>
    <div>
        <MainLayout>
            <form @submit.prevent="submitForm" class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow space-y-4 mt-10">
                <h1 class="text-center my-5">System Settings</h1>
                
                <!-- Success Message -->
                <div v-if="$page.props.flash.success" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ $page.props.flash.success }}
                </div>
                
                <div>
                    <label for="renew_time" class="block text-sm font-medium text-gray-700 mb-1">Renew Time</label>
                    <input type="text" id="renew_time" name="renew_time" v-model="form.renew_time"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter renew time (e.g., 30 days)" />
                </div>

                <div class="relative">
                    <label for="master_password" class="block text-sm font-medium text-gray-700 mb-1">Master Password</label>
                    <div class="flex items-center">
                        <input :type="showPassword ? 'text' : 'password'" id="master_password" name="master_password" 
                            v-model="form.master_password"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter master password" />
                        <button type="button" @click="togglePasswordVisibility" 
                            class="ml-2 p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                            <span v-if="showPassword">👁️</span>
                            <span v-else>👁️‍🗨️</span>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Settings
                    </button>
                </div>
            </form>
        </MainLayout>
    </div>
</template>

<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    setting: {
        type: Object,
        required: true,
    },
});

const form = ref({
    renew_time: props.setting?.renew_time || '',
    master_password: props.setting?.master_password || ''
});

const showPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const submitForm = () => {
    router.put('/setting', form.value, {
        preserveScroll: true,
        onSuccess: () => {
            // Success message will be shown from the flash message
        },
    });
};

onMounted(() => {
    console.log(props.setting);
});
</script>