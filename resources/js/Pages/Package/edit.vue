<template>
    <div>
        <MainLayout>
            <form class="w-1/2 mx-auto p-6 bg-white rounded-2xl shadow space-y-4 mt-10"
                @submit.prevent="updatePackage">
                <h1 class="text-center my-5">Edit Package</h1>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="name" name="name" v-model="form.name"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter name" />
                    <p v-if="errors.name" class="text-red-600 text-sm mt-1">
                        {{ errors.name }}
                    </p>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" id="price" name="price" v-model="form.price"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="1000" />
                    <p v-if="errors.price" class="text-red-600 text-sm mt-1">
                        {{ errors.price }}
                    </p>
                </div>
                <div>
                    <label for="duration_type" class="block text-sm font-medium text-gray-700 mb-1">Duration
                        Type</label>
                    <select id="duration_type" v-model="form.duration_type"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option disabled value="">Select duration</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    <p v-if="errors.duration_type" class="text-red-600 text-sm mt-1">
                        {{ errors.duration_type }}
                    </p>
                </div>
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                    <input type="number" id="duration" name="duration" v-model="form.duration"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <p v-if="errors.duration" class="text-red-600 text-sm mt-1">
                        {{ errors.duration }}
                    </p>
                </div>
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea type="text" id="url" name="url" v-model="form.description"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <p v-if="errors.description" class="text-red-600 text-sm mt-1">
                        {{ errors.description }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="is_free" name="is_free" v-model="form.is_free"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                    <label for="is_free" class="text-sm text-gray-700 ml-2">Free</label>
                </div>
                <!--Software selection-->
                 <h3 class="text-center my-5">Our Softwares</h3>
                <div v-if="softwares.length" class="grid grid-cols-2 sm:grid-cols-3 gap-4 overflow-hidden">
                    <div v-for="software in softwares" :key="software.id" class="mb-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" :value="software.id" v-model="form.selectedSoftwares"
                                class="form-checkbox" />
                            <span class="text-sm ml-1">{{ software.name }}</span>
                        </label>
                    </div>
                </div>

                <div v-else class="text-gray-500">
                    No software available. Please add some first.
                </div>
                <!-- Submit -->
                <div></div>
                <!-- Submit -->
                <div>
                    <button type="submit" :disabled="processing"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                        Submit
                    </button>
                </div>
            </form>
        </MainLayout>
    </div>
</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3'
import { ref, defineProps, onMounted } from 'vue';

const props = defineProps({
    ourPackage: {
        type: Object,
        required: true,
    },
    softwares:{
        type:Object,
        default: ()=> []
    },
    selectedSoftwares:{
        type:Array,
        default: ()=> []
    }
});

onMounted(() => {
    console.log(props.ourPackage);
});

const form = useForm({
    name: props.ourPackage.name || '',
    duration_type: props.ourPackage.duration_type || '',
    duration: props.ourPackage.duration || '',
    description: props.ourPackage.description || '',
    price: props.ourPackage.price || '',
    is_free: props.ourPackage.is_free || true,
    selectedSoftwares: props.selectedSoftwares || [],
})


let errors = ref({});
const updatePackage = () => {
    form.put(route('package.update', props.ourPackage.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Update successful!')
        },
        onError: () => {
            errors.value = form.errors;
            console.error('Validation errors:', form.errors)
        }
    })
}


const processing = form.processing


</script>