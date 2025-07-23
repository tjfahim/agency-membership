<template>
    <div>
        <MainLayout>
            <form class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow space-y-4 mt-10">
                <h1 class="text-center my-5">Our Software</h1>
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="name" name="name" v-model="software.name"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled placeholder="Enter name" />
                </div>

                <!-- URL -->
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                    <input type="text" id="url" name="url" v-model="software.url"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled placeholder="https://example.com" />
                </div>

                <!-- Is Active -->
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="is_active" name="is_active" v-model="isActiveCheckbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                    <label for="is_active" class="text-sm text-gray-700 ml-2">Active</label>
                </div>
            </form>

        </MainLayout>
    </div>
</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { ref,  defineProps, computed } from 'vue';

const showMessage = ref(false);

const props = defineProps({
    software: {
        type: Object,
        required: true,
    },
    flash: {
        type: Object,
    }
});

const software = ref(props.software);

const isActiveCheckbox = computed({
  get: () => props.software.is_active === 1,
  set: (val) => {
    props.software.is_active = val ? 1 : 0;
  }
});
</script>