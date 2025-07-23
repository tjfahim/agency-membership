<template>
    <div>
        <MainLayout>
            <form class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow space-y-4 mt-10">
                <h1 class="text-center my-5">Our Package</h1>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="name" name="name" v-model="ourPackage.name"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled placeholder="Enter name" />
                </div>

                <div>
                    <label for="duration_type" class="block text-sm font-medium text-gray-700 mb-1">Duration
                        Type</label>
                    <input type="text" id="duration_type" name="duration_type" v-model="ourPackage.duration_type"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled />
                </div>
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                    <input type="text" id="duration" name="duration" v-model="ourPackage.duration"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled />
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="text" id="price" name="price" v-model="ourPackage.duration"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled />
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="is_free" name="is_free" v-model="isActiveCheckbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                    <label for="is_free" class="text-sm text-gray-700 ml-2">Free</label>
                </div>
                 <h3 class="text-center my-2">Our Softwares</h3>
                <div class="flex flex-wrap gap-2 my-4" v-if="softwares.length">
                    <span v-for="software in softwares" :key="software.id"
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition duration-200">
                        {{ software.name }}
                    </span>
                </div>
            </form>

        </MainLayout>
    </div>
</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { ref, defineProps, computed, onMounted } from 'vue';

const props = defineProps({
    ourPackage: {
        type: Object,
        required: true,
    },
    softwares: {
        type: Array,
        default: () => [],
    }
});

const ourPackage = ref(props.ourPackage);

const isActiveCheckbox = computed({
    get: () => props.ourPackage.is_free === 1,
    set: (val) => {
        props.ourPackage.is_free = val ? 1 : 0;
    }
});

onMounted(() => {
    console.log(props.ourPackage);
});
</script>