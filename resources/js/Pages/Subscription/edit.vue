<template>
    <div>
        <MainLayout>
            <form class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow space-y-4 mt-10"
                @submit.prevent="updateSubscription">
                <h1 class="text-center my-5">Edit Subscription</h1>
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700 mb-1">User
                    </label>
                    <select id="user" v-model="form.user_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option disabled value="" selected>Select User</option>
                        <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.name }}</option>
                    </select>
                    <p v-if="errors.user_id" class="text-red-600 text-sm mt-1">
                        {{ errors.user_id }}
                    </p>
                </div>
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Package
                    </label>
                    <select id="user" v-model="form.package_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option disabled value="" selected>Select Package</option>
                        <option v-for="(ourPackage, index) in packages" :key="index" :value="ourPackage.id">{{
                            ourPackage.name }}</option>
                    </select>
                    <p v-if="errors.package_id" class="text-red-600 text-sm mt-1">
                        {{ errors.package_id }}
                    </p>
                </div>
                <div>
                    <label for="start_at" class="block text-sm font-medium text-gray-700 mb-1">Starts At</label>
                    <input type="date" id="start_at" name="start_at" v-model="form.start_at"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <p v-if="errors.start_at" class="text-red-600 text-sm mt-1">
                        {{ errors.start_at }}
                    </p>
                </div>
                <div>
                    <label for="end_at" class="block text-sm font-medium text-gray-700 mb-1">End At</label>
                    <input type="date" id="end_at" name="end_at" v-model="form.end_at"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <p v-if="errors.end_at" class="text-red-600 text-sm mt-1">
                        {{ errors.end_at }}
                    </p>
                </div>
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status
                    </label>
                    <select id="payment_status" v-model="form.payment_status"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option disabled value="">Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Unpaid">Unpaid</option>
                    </select>
                    <p v-if="errors.payment_status" class="text-red-600 text-sm mt-1">
                        {{ errors.payment_status }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="auto_renew" name="auto_renew" v-model="form.auto_renew"
                        class="w-4 h-4 border rounded-lg" :true-value="1" :false-value="0" />
                    <label for="auto_renew" class="block text-sm font-medium text-gray-700 ml-2">Auto Renew</label>

                    <p v-if="errors.auto_renew" class="text-red-600 text-sm mt-1">
                        {{ errors.auto_renew }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="is_active" name="is_active" v-model="form.is_active"
                        class="w-4 h-4 border rounded-lg" :true-value="1" :false-value="0" />
                    <label for="is_active" class="block text-sm font-medium text-gray-700 ml-2">Active</label>

                    <p v-if="errors.is_active" class="text-red-600 text-sm mt-1">
                        {{ errors.is_active }}
                    </p>
                </div>

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
import { ref, onMounted, defineProps } from 'vue';

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
    const userResponse = await fetch(route('users.no_role'));
    const userData = await userResponse.json();
    users.value = userData;

    //fetch packages
    const ourPackagesResponse = await fetch(route('ourPackages.index'));
    const packageData = await ourPackagesResponse.json();
    packages.value = packageData;
    console.log(packages.value);

});
let errors = ref({});

function updateSubscription() {
    form.put(route('subscription.update',props.subscription.id), {
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