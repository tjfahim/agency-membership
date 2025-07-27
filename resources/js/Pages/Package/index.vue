<template>
    <MainLayout>
        <div v-if="showMessage" class="alert alert-success">
            {{ flash?.success }}
        </div>
        <div class="p-6">
            <ViewPackage :companyPackage="companyPackage" :companySoftwares="companySoftwares"  :popUpModalView="popUpModalView"
                @close="popUpModalView=false"></ViewPackage>
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Package List</h1>
                <Link href="package/create"
                    class="block px-4 py-2 ml-5 !text-slate-200 !no-underline rounded bg-blue-600 hover:bg-gray-700 text-lg">
                Create Package
                </Link>
            </div>
            <div v-if="packages.length === 0" class="text-gray-500">
                No packages found.
            </div>
            <table class="w-full table-auto border-collapse border border-gray-300" v-else>
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Name</th>
                        <th class="border px-4 py-2 text-left">
                            Duration Type
                        </th>
                        <th class="border px-4 py-2 text-left">Duration</th>
                        <th class="border px-4 py-2 text-left">Price</th>
                        <th class="border px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(myPackage, index) in filteredPackage" :key="index" class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ myPackage.name }}</td>
                        <td class="border px-4 py-2">
                            {{ myPackage.duration_type }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ myPackage.duration }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ myPackage.price }} tk
                        </td>
                        <td
                            class="flex justify-center items-center border md:px-4 md:py-2 text-center space-x-2 md:space-x-2 xl:space-x-4">
                            <button @click="showPackage(myPackage)" class="text-blue-600 hover:underline "><i
                                    class="las la-eye md:text-md lg:text-lg xl:text-2xl text-green-500 mr-3"></i></button>
                            <Link :href="`/package/${myPackage.id}/edit`" class="text-yellow-600 hover:underline"><i
                                class="las la-edit md:text-md lg:text-lg xl:text-2xl"></i></Link>
                            <button @click="deletePackage(myPackage.id)" class="text-red-600 hover:underline">
                                <i class="las la-trash md:text-md lg:text-lg xl:text-2xl"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination v-if="packages.data.length > 0" :pagination="pagination" @change-page="changePage"></Pagination>
        </div>
    </MainLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import axios from "axios";
import MainLayout from "@/components/layouts/MainLayout.vue";
import Pagination from "../../components/Pagination.vue";
import { defineProps, ref, computed, onMounted, watch } from "vue";
import ViewPackage from "../../components/Package/view.vue";
const props = defineProps({
    packages: Array,
    flash: Object,
    pagination: Object,
});

const is_delete = ref(false);
const deletedId = ref(null);
const showMessage = ref(false);

function deletePackage(id) {
    if (confirm("Are you sure you want to delete this package?")) {
        is_delete.value = true;
        deletedId.value = id;
        router.delete(`/package/${id}`, {
            onSuccess: () => {
                console.log("deleted Successfuly");
            },
        });
    }
}
const companyPackage = ref({});
const companySoftwares = ref([]);
const popUpModalView= ref(false);
const showPackage = async (myPackage) => {
    try {
        const response = await axios.get('/package/' + myPackage.id);
        console.log(response);
        companyPackage.value = response.data.ourPackage;
        companySoftwares.value = response.data.softwares;
        popUpModalView.value = true;
    }catch(err){
        console.log("Failed to fetch response",err);
    }
}
const filteredPackage = computed(() => {
    if (is_delete.value === true && deletedId.value !== null) {
        return props.packages.data.filter(
            (myPackage) => myPackage.id != deletedId.value
        );
    }
    return props.packages.data;
});

onMounted(() => {
    if (props.flash?.success) {
        showFlash();
    }
    console.log(props.packages);
});

const showFlash = () => {
    showMessage.value = true;
    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
};

watch(
    () => props.flash?.success,
    (newVal) => {
        if (newVal) {
            showFlash();
        }
    }
);
const changePage = (page) => {
    router.get(route('package.index', { page }, { preserveState: true }));
}
</script>
