<template>
    <div class="overflow-x-auto p-4">
       
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                <input v-model="filters.username" placeholder="Enter username"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Software -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Software</label>
                <input v-model="filters.software" placeholder="Enter software"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Package -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Package</label>
                <input v-model="filters.package" placeholder="Enter package"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Last Payment Date -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Last Payment Date</label>
                <input type="date" v-model="filters.last_payment_date"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Expire Date -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Expire Date</label>
                <input type="date" v-model="filters.expire_date"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Auto Renew -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Auto Renew</label>
                <select v-model="filters.auto_renew"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                <select v-model="filters.status"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select</option>
                    <option value="Paid">Paid</option>
                    <option value="Due">Due</option>
                    <option value="Pending">Pending</option>
                    <option value="Unpaid">Unpaid</option>
                </select>
            </div>

            <!-- Price -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Price</label>
                <input v-model="filters.price" type="number" placeholder="Enter price"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <!-- Submit Button -->
            <div class="mt-1 flex justify-end">
                <button @click="applyFilters"
                    class="bg-blue-600 text-white font-semibold px-6 py-3 btnRadius mb-5 shadow hover:shadow-md transition duration-200">
                    🔍 Apply Filters
                </button>
            </div>
        </div>


        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden text-sm md:text-base">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="px-4 py-2">Username</th>
                    <th class="px-4 py-2">Software</th>
                    <th class="px-4 py-2">Package</th>
                    <th class="px-4 py-2">Payment Date</th>
                    <th class="px-4 py-2">Expire Date</th>
                    <th class="px-4 py-2">Auto Renew</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in filteredUsers" :key="index" class="hover:bg-gray-50 border-b">
                    <td class="px-4 py-2 whitespace-nowrap">{{ item?.subscription?.user?.name }}</td>
                    <td class="px-4 py-2">
                        <span v-if="item.subscription?.package?.softwares.length > 0" v-for="software in
                            item.subscription?.package?.softwares
                            " :key="software?.name">
                            {{ software?.name }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ item.subscription?.package?.name }}</td>
                    <td class="px-4 py-2">{{ item.created_at_format }}</td>
                    <td class="px-4 py-2">{{ item?.subscription?.end_at }}</td>
                    <td class="px-4 py-2">
                        <span :class="item?.subscription?.auto_renew ? 'text-green-600' : 'text-red-500'">
                            {{ item.auto_renew ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <span
                            :class="item?.subscription?.payment_status === 'Paid' ? 'text-green-600' : 'text-red-500'">
                            {{ item?.subscription?.payment_status }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ item?.amount }} tk</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted, computed, ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";


const props = defineProps({
    users: Object,
    pagination: Object,
});
const filters = useForm({
    username: '',
    software: '',
    package: '',
    last_payment_date: '',
    expire_date: '',
    auto_renew: '',
    status: '',
    price: '',
});
const is_delete = ref(false);
onMounted(() => {
    console.log(props.users, props.pagination);
});

const filteredUsers = computed(() => {
    if (is_delete.value === true && deletedId.value !== null) {
        return props.users.data.filter(
            (myPackage) => myPackage.id != deletedId.value
        );
    }
    return props.users.data;
});

const applyFilters = () => {
    router.get(route('dashboard'),filters,{
        replace:true,
        preserveState:true,
    });
}
</script>
<style>
.btnRadius {
    border-radius: 10px;
}
</style>
