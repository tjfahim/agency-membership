<template>
    <div>
        <MainLayout>
            <div class="w-full max-w-7xl mx-auto mt-10 px-6">
                <form
                    class="bg-white border border-gray-200 rounded-2xl shadow-xl p-8 grid grid-cols-1 md:grid-cols-2 gap-6"
                    @submit.prevent="updateSubscription">
                    <h1 class="text-2xl font-semibold text-gray-800 col-span-full text-center">Edit Subscription</h1>

                    <!-- User Select -->
                    <div class="col-span-1">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                        <select id="user_id" v-model="form.user_id"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option disabled value="">Select User</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}
                            </option>
                        </select>
                        <p v-if="errors.user_id" class="text-red-600 text-sm mt-1">{{ errors.user_id }}</p>
                    </div>

                    <!-- Package Select -->
                    <div class="col-span-1">
                        <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Package</label>
                        <select id="package_id" v-model="form.package_id"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option disabled value="">Select Package</option>
                            <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">{{ pkg.name }}
                            </option>
                        </select>
                        <p v-if="errors.package_id" class="text-red-600 text-sm mt-1">{{ errors.package_id }}</p>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" id="start_at" v-model="form.start_at"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <p v-if="errors.start_at" class="text-red-600 text-sm mt-1">{{ errors.start_at }}</p>
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_at" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" id="end_at" v-model="form.end_at"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <p v-if="errors.end_at" class="text-red-600 text-sm mt-1">{{ errors.end_at }}</p>
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment
                            Status</label>
                        <select id="payment_status" v-model="form.payment_status"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option disabled value="">Select Status</option>
                            <option value="Paid">Paid</option>
                            <option value="Due">Due</option>
                            <option value="Pending">Pending</option>
                            <option value="Unpaid">Unpaid</option>
                        </select>
                        <p v-if="errors.payment_status" class="text-red-600 text-sm mt-1">{{ errors.payment_status }}
                        </p>
                    </div>

                    <!-- Checkboxes -->
                    <div class="flex items-center gap-6 col-span-full">
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" v-model="form.auto_renew"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                :true-value="1" :false-value="0" />
                            <span class="text-sm text-gray-700 ml-2">Auto Renew</span>
                        </label>

                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" v-model="form.is_active"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                :true-value="1" :false-value="0" />
                            <span class="text-sm text-gray-700 ml-2">Active</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-span-full">
                        <button type="submit" :disabled="processing"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 ease-in-out disabled:opacity-50">
                            {{ processing ? 'Updating...' : 'Update Subscription' }}
                        </button>
                    </div>
                </form>
            </div>
        </MainLayout>

    </div>
</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted, defineProps } from 'vue';
import axios from 'axios';

const props = defineProps({
    subscription: {
        type: Object,
        required: true,
    }
});
const form = useForm({
    user_id: props.subscription.user.id || '',
    package_id: props.subscription.package.id || '',
    start_at: props.subscription.start_at || '',
    end_at: props.subscription.end_at || '',
    is_active: props.subscription.is_active || false,
    auto_renew: props.subscription.auto_renew || false,
    payment_status: props.subscription.payment_status || '',
});
const users = ref([]);
const packages = ref([]);

onMounted(async () => {
    
    //fetch users
    // const userResponse = await fetch(route('users.no_role'));
    // const userData = await userResponse.json();
    // users.value = userData;

    //fetch packages
     try {
        const response = await axios.get('/get_packages_payment');
        packages.value = response.data;
        console.log(packages.value);
    } catch (error) {
        console.error('failed to fetch packages', error);
    }
    try{
        const userResponse = await axios.get('/users-without-roles');
        users.value = userResponse.data;
    }catch(error){
    console.error('failed to fetch users', error);
    }

});
let errors = ref({});

function updateSubscription() {
    form.put(route('subscription.update', props.subscription.id), {
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