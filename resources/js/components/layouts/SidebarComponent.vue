<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'

const isSoftwareOpen = ref(false);
const isPackageOpen = ref(false);
const isSubscriptionOpen = ref(false);
const isPaymentOpen = ref(false);
const isUserOpen = ref(false);

const user = ref('');

onMounted(async ()=>{
    const response = await fetch(route('user'));
    const data = await response.json();
    user.value = data.name;
});
defineOptions({
    name: 'Sidebar'
});
</script>

<template>
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="ml-6 my-2">
                    <Link :href="`/`" class="!text-gray-400 !no-underline hover:!no-underline  hover:!text-gray-200 flex gap-2">
                Dashboard</Link>
                </div>
                <!-- Software -->
                <div @click="isSoftwareOpen = !isSoftwareOpen"
                    class="flex items-center justify-between cursor-pointer px-4 py-2 hover:bg-gray-700  my-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-columns"></i>
                        <span>Software</span>
                    </div>
                    <span v-if="isSoftwareOpen"><i class="fas fa-angle-down transition-transform duration-300"></i></span>
                    <span v-else><i class="fas fa-angle-right transition-transform duration-300"></i></span>
                </div>

                <!-- Nested Links -->
                <div v-if="isSoftwareOpen" class="ml-6  transition-all mb-3">
                    <Link :href="`/software`" class="block px-4  py-1 ml-5  !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Index Software</Link>
                    <Link :href="`/software/create`" class="block px-4 py-1  ml-5 !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Create Software</Link>
                </div>
                <!-- End Software-->
                 <!-- Pakcage -->
                <div @click="isPackageOpen = !isPackageOpen"
                    class="flex items-center justify-between cursor-pointer px-4 hover:bg-gray-700 my-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-columns"></i>
                        <span>Package</span>
                    </div>
                   <span v-if="isPackageOpen"><i class="fas fa-angle-down transition-transform duration-300"></i></span>
                    <span v-else><i class="fas fa-angle-right transition-transform duration-300"></i></span>
                </div>

                <!-- Nested Links -->
                <div v-if="isPackageOpen" class="ml-6  transition-all mb-3">
                    <Link :href="`/package`" class="block px-4 py-1  ml-5 !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Index Package</Link>
                    <Link :href="`/package/create`" class="block px-4 py-1  ml-5 !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Create Package</Link>
                </div>
                <!-- End Pakcage-->

                <!-- Subscription -->
                <div @click="isSubscriptionOpen = !isSubscriptionOpen"
                    class="flex items-center justify-between cursor-pointer px-4   hover:bg-gray-700  my-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-columns"></i>
                        <span>Subscription</span>
                    </div>
                   <span v-if="isSubscriptionOpen"><i class="fas fa-angle-down transition-transform duration-300"></i></span>
                    <span v-else><i class="fas fa-angle-right transition-transform duration-300"></i></span>
                </div>

                <!-- Nested Links -->
                <div v-if="isSubscriptionOpen" class="ml-6  transition-all mb-3">
                    <Link :href="`/subscription`" class="block px-4 py-1  ml-5 !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Index Subscription</Link>
                </div>
                <!-- End Subscriptions-->

                <!-- Payment -->
                <div @click="isPaymentOpen = !isPaymentOpen"
                    class="flex items-center justify-between cursor-pointer px-4   hover:bg-gray-700  my-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-columns"></i>
                        <span>Payment</span>
                    </div>
                    <span v-if="isPaymentOpen"><i class="fas fa-angle-down transition-transform duration-300"></i></span>
                    <span v-else><i class="fas fa-angle-right transition-transform duration-300"></i></span>
                </div>

                <!-- Nested Links -->
                <div v-if="isPaymentOpen" class="ml-6  transition-all mb-3">
                    <Link :href="`/payment`" class="block px-4 py-1  ml-5 !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Index Payment</Link>
                    <Link :href="`/payment/create`" class="block px-4 py-1  ml-5 !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Create Payment</Link>
                </div>
                <!-- End Payment-->
                <!-- User -->
                <div @click="isUserOpen = !isUserOpen"
                    class="flex items-center justify-between cursor-pointer px-4   hover:bg-gray-700  my-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-columns"></i>
                        <span>User</span>
                    </div>
                    <span v-if="isUserOpen"><i class="fas fa-angle-down transition-transform duration-300"></i></span>
                    <span v-else><i class="fas fa-angle-right transition-transform duration-300"></i></span>
                </div>

                <!-- Nested Links -->
                <div v-if="isUserOpen" class="ml-6  transition-all mb-3">
                    <Link :href="`/users`" class="block px-4 py-1  ml-5 !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Index User</Link>
                     <Link :href="`/users/create`" class="block px-4 py-1  ml-5 !text-slate-300 !no-underline rounded hover:bg-gray-700 text-sm">Create User</Link>
                </div>
                <!-- End Payment-->
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as: {{ user }}</div>
            Start Bootstrap
        </div>
    </nav>
</template>
