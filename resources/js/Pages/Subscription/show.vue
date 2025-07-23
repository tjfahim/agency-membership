<template>
    <div>
        <MainLayout>
            <form class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow space-y-4 mt-10">
                  <h1 class="text-center my-5">Subscription</h1>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <input type="text" id="name" name="name" v-model="subscription.user.name"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled placeholder="Enter name" />
                </div>

                <div>
                    <label for="package.name" class="block text-sm font-medium text-gray-700 mb-1">Package</label>
                    <input type="text" id="package.name" name="package.name" v-model="subscription.package.name"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled />
                </div>
                 <div>
                    <label for="start_at" class="block text-sm font-medium text-gray-700 mb-1">Start At</label>
                    <input type="date" id="start_at" name="start_at" v-model="subscription.start_at"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled />
                </div>
                <div>
                    <label for="end_at" class="block text-sm font-medium text-gray-700 mb-1">End At</label>
                    <input type="date" id="end_at" name="end_at" v-model="subscription.end_at"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled />
                </div>
                 <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <input type="text" id="payment_status" name="payment_status" v-model="subscription.payment_status"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled />
                </div>
                 <div class="flex items-center space-x-2">
                    <input type="checkbox" id="auto_renew" name="auto_renew" v-model="isAutoRenewCheckbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                    <label for="auto_renew" class="text-sm text-gray-700 ml-2">Auto Renew</label>
                </div>
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
import { ref,  defineProps, computed, onMounted } from 'vue';

const props = defineProps({
    subscription: {
        type: Object,
        required: true,
    },
});


const isActiveCheckbox = computed({
  get: () => props.subscription.is_active === 1,
  set: (val) => {
    props.subscription.is_free = val ? 1 : 0;
  }
});

const isAutoRenewCheckbox = computed({
    get:  ()=> props.subscription.auto_renew === 1,
    set: (val) => {
        props.subscription.auto_renew = val ? 1 : 0;
    }
});

onMounted(()=>{
    console.log(props.subscription);
});
</script>