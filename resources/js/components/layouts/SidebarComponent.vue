<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const isSoftwareOpen = ref(false)
const isPackageOpen = ref(false)
const isSubscriptionOpen = ref(false)
const isPaymentOpen = ref(false)
const isUserOpen = ref(false)

const user = ref('')
const currentPath = ref(window.location.pathname)

onMounted(async () => {
    const response = await fetch(route('user'))
    const data = await response.json()
    user.value = data.name
})

defineOptions({
    name: 'Sidebar'
})
</script>

<template>
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class=" my-2">
                       <Link
                    :href="`/`"
                    :class="[
                        'flex items-center justify-between cursor-pointer px-4 py-2 my-2 !text-slate-300 !no-underline',
                        currentPath==='/' ? 'bg-gray-700 text-white' : 'hover:bg-gray-700'
                    ]"
                >
                    <div class="flex items-center gap-2">
                        <i class="fa fa-tachometer"></i><span> Dashboard</span>
                    </div>
                </Link>
                   
                </div>
                <!-- Users -->
                <Link
                    :href="`/users`"
                    :class="[
                        'flex items-center justify-between cursor-pointer px-4 py-2 my-2 !text-slate-300 !no-underline',
                        currentPath.startsWith('/users') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700'
                    ]"
                >
                    <div class="flex items-center gap-2">
                        <i class="fa fa-users"></i><span>User Manage</span>
                    </div>
                </Link>
             

                <!-- Package -->
                <Link
                    :href="`/package`"
                    :class="[
                        'flex items-center justify-between cursor-pointer px-4 py-2 my-2 !text-slate-300 !no-underline',
                        currentPath.startsWith('/package') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700'
                    ]"
                >
                    <div class="flex items-center gap-2">
                       <i class="fa fa-box-open"></i> <span>Package Manage</span>
                    </div>
                </Link>

             

                <!-- Payment -->
                <Link
                    :href="`/payment`"
                    :class="[
                        'flex items-center justify-between cursor-pointer px-4 py-2 my-2 !text-slate-300 !no-underline',
                        currentPath.startsWith('/payment') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700'
                    ]"
                >
                    <div class="flex items-center gap-2">
                        <i class="fa fa-credit-card"></i><span>Payment Manage</span>
                    </div>
                </Link>
                   <!-- Subscription -->
                <Link
                    :href="`/subscription`"
                    :class="[
                        'flex items-center justify-between cursor-pointer px-4 py-2 my-2 !text-slate-300 !no-underline',
                        currentPath.startsWith('/subscription') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700'
                    ]"
                >
                    <div class="flex items-center gap-2">
                       <i class="fa fa-sync-alt"></i> <span>Subscription Manage</span>
                    </div>
                </Link>
                <!-- Software -->
                <Link
                    :href="`/software`"
                    :class="[
                        'flex items-center justify-between cursor-pointer px-4 py-2 my-2 !text-slate-300 !no-underline',
                        currentPath.startsWith('/software') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700'
                    ]"
                >
                    <div class="flex items-center gap-2">
                        <i class="fa fa-code"></i><span> Software Manage</span>
                    </div>
                </Link>
                <!-- Report -->
                <Link
                    :href="`/report`"
                    :class="[
                        'flex items-center justify-between cursor-pointer px-4 py-2 my-2 !text-slate-300 !no-underline',
                        currentPath.startsWith('/report') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700'
                    ]"
                >
                    <div class="flex items-center gap-2">
                        <i class="fa fa-file"></i><span>Report Manage</span>
                    </div>
                </Link>

            </div>
        </div>

        <!-- Optional Footer -->
        <!--
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as: {{ user }}</div>
        </div>
        -->
    </nav>
</template>
