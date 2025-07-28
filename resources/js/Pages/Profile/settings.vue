<!-- resources/js/Pages/Settings/SettingsPage.vue -->
<template>
    <MainLayout>
        <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
            <h2 class="text-2xl  font-bold text-[#0F1551] mb-6">Settings</h2>
            <div v-if="flashMessage"
                :class="[
                    ' px-4 py-2 w-full my-2 text-white font-semibold text-lg rounded shadow-md z-50 transition',
                    flashType === 'success'
                        ? ' bg-green-800'
                        : ' bg-red-800',
                ]"
            >
                {{ flashMessage }}
            </div>
            <!-- Tabs -->
            <div class="flex space-x-4 border-b mb-6">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    @click="activeTab = tab.key"
                    :class="[
                        'pb-2 px-4 text-sm font-semibold focus:outline-none',
                        activeTab === tab.key
                            ? 'border-b-2 border-blue-600 text-blue-600'
                            : 'text-gray-600 hover:text-blue-600',
                    ]"
                >
                    {{ tab.label }}
                </button>
            </div>

            <!-- Tab Content -->
            <div>
                <component
                    :is="activeComponent"
                    :user="user"
                    :profile="profile"
                />
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "../../components/layouts/MainLayout.vue";
import ProfileSettings from "../../Pages/Profile/partial/Profile.vue";
import PasswordSettings from "../../Pages/Profile/partial/Password.vue";
import { ref, computed, onMounted, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const activeTab = ref("profile");
const props = defineProps({
    user: Object,
    profile: Object,
});
const flashMessage = ref("");
const flashType = ref("success");
watch(
   () => page.props.flash,
     (flash) => {
    if (flash?.success || flash?.error) {
      flashMessage.value = flash.success || flash.error
      flashType.value = flash.success ? 'success' : 'error'

      setTimeout(() => {
        flashMessage.value = ''
      }, 5000)
    }
  },
    { immediate: true }
);
const tabs = [
    { key: "profile", label: "Profile Settings" },
    { key: "password", label: "Change Password" },
];

const activeComponent = computed(() =>
    activeTab.value === "password" ? PasswordSettings : ProfileSettings
);
</script>