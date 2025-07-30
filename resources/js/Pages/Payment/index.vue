<template>
    <MainLayout>
        <!-- Success flash message -->
        <div v-if="showMessage" class="alert alert-success mb-4">
            {{ flash?.success }}
        </div>
        <div v-if="flash.error" class="text-red-500">
            {{ flash.error }}
        </div>
        <div class="p-6 relative">
           <div class="flex justify-between">
             <h1 class="text-2xl font-bold mb-4">Payment List</h1>

            <Button @click="createModal"
                class="block px-4 py-2 ml-5 !text-slate-200 !no-underline rounded bg-amber-600 hover:bg-amber-700 text-lg mb-4">
                Create Payment
            </Button>
           </div>

            <!-- Create payment modal -->
            <CreatePayment :popUpModalCreate="popUpModalCreate" @close="handleCreateModalClose" />
            <!--Edit payment modal-->
            <EditPayment :popUpModalEdit="popUpModalEdit" @close="handleEditModalClose" :packages="packages"
                :users="users" :paymentData="paymentData" />
            <ViewPayment :popUpModalView="popUpModalView"   :packages="packages"
                :users="users" :paymentData="paymentData" />
            <!-- Data table -->
            <vue-good-table :columns="columns" :rows="filteredPayments"
                :search-options="{ enabled: true, placeholder: 'Search payments...' }" :pagination-options="{
                    enabled: true,
                    perPage: 10,
                    perPageDropdown: [5, 10, 20, 50],
                    dropdownAllowAll: false
                }" :sort-options="{ enabled: true }" :styleClass="'vgt-table bordered'">
                <template #table-row="{ row, column }">

                    <td v-if="column.field === 'actions'" class="text-center py-2">
                        <div class="flex justify-center space-x-4">
                            <!-- <button @click="viewModal(row.id)" class="pr-3 text-blue-600 hover:underline" title="View Payment">
                <i class="las la-eye text-green-500 text-xl"></i>
              </button> -->
                            <button @click="editModal(row.id)" class="text-yellow-600 hover:underline pr-3"
                                title="Edit Payment">
                                <i class="las la-edit text-xl"></i>
                            </button>
                            <button @click="deletePayment(row.id)" class="text-red-600 hover:underline"
                                title="Delete Payment">
                                <i class="las la-trash text-xl"></i>
                            </button>
                        </div>
                    </td>
                    <td v-else>
                        {{ row[column.field] }}
                    </td>
                </template>
            </vue-good-table>

            <!-- Optional Pagination component if you want to sync server pagination -->
            <!--
      <Pagination
        v-if="pagination && pagination.total > pagination.per_page"
        :pagination="pagination"
        @change-page="changePage"
      />
      -->
        </div>
    </MainLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import MainLayout from '@/components/layouts/MainLayout.vue'
import CreatePayment from '../../components/Payment/create.vue'
import EditPayment from '../../components/Payment/edit.vue'
import ViewPayment from '../../components/Payment/view.vue'
import { defineProps, ref, computed, onMounted, watch } from 'vue'
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'
import axios from 'axios'

const props = defineProps({
    payments: Object,   // expects paginated data { data: [...] }
    flash: Object,
    pagination: Object,
})

const showMessage = ref(false)
const showError = ref(false)
const popUpModalCreate = ref(false)
const popUpModalEdit = ref(false);
const popUpModalView = ref(false);
const isDelete = ref(false)
const deletedId = ref(null)

const createModal = () => {
    popUpModalCreate.value = !popUpModalCreate.value
}

const paymentData = ref({});
const packages = ref([]);
const users = ref([]);

const handleEditModalClose = () => {
   popUpModalEdit.value = false;
  paymentData.value = {};
}
const handleCreateModalClose = ()=>{
   setTimeout(() => {
    popUpModalCreate.value = false
  }, 0)
}
const viewModal = async (id) => {
    try {
        const response = await axios.get(`payment/${id}`);
        paymentData.value = response.data.payment;
        popUpModalView.value = true;
        console.log(paymentData.value);
    } catch (error) {
        console.log("failed to load data:", error);
    }
}
const editModal = async (id) => {
    try {
        const response = await axios.get(`/payment/${id}/edit`);
        // Assign data from response
        paymentData.value = response.data.payment;
        packages.value = response.data.packages;
        users.value = response.data.users;
        console.log(paymentData.value);
        popUpModalEdit.value = true; 
    } catch (error) {
        console.error('Failed to load payment data:', error);
    }
};
function deletePayment(id) {
    if (confirm('Are you sure you want to delete this payment?')) {
        isDelete.value = true
        deletedId.value = id
        router.delete(`/payment/${id}`, {
            onSuccess: () => {
                console.log('Deleted successfully')
            },
        });
        router.visit(route('payment.index'));
    }
}

// Columns definition for datatable
const columns = [
    { label: 'User', field: 'userName', sortable: true },
    { label: 'Amount', field: 'amount', sortable: true },
    { label: 'Package Name', field: 'package_name', sortable: true },
    { label: 'Payment Method', field: 'payment_method', sortable: true },
    { label: 'Status', field: 'payment_status', sortable: true },
    { label: 'Actions', field: 'actions', sortable: false },
]

// Map payment data and computed filteredPayments excluding deleted one
const filteredPayments = computed(() => {
    let data = props.payments?.data || []

    // Map userName for display (flatten)
    data = data.map((p) => ({
        ...p,
        userName: p.user?.name || 'N/A',
        package_name: p.subscription?.package?.name || 'N/A',
        amount: p.subscription?.package?.is_free ? 'Free' : p.amount
    }))

    if (isDelete.value && deletedId.value !== null) {
        return data.filter((payment) => payment.id !== deletedId.value)
    }
    return data
})

onMounted(() => {
    if (props.flash?.success) {
        showFlash()
    }
    console.log(props.payments);
})

const showFlash = () => {
    showMessage.value = true
    setTimeout(() => {
        showMessage.value = false
    }, 3000)
}

watch(() => props.flash?.software_empty, (newVal) => {
    if (newVal) {
        showError.value = true
        setTimeout(() => {
            showError.value = false
        }, 3000)
    }
})

watch(() => props.flash?.success, (newVal) => {
    if (newVal) {
        showFlash()
    }
})

// Pagination change handler (optional if you want to sync server paging)
const changePage = (page) => {
    router.get(route('payment.index', { page }, { preserveState: true }))
}
</script>

<style>
.vgt-table.bordered td,
.vgt-table.bordered th {
    border: 1px solid #57514fa6;
}

.vgt-table.bordered td td {
    border: none;
}

table.vgt-table td {
    padding: 0.2em !important;
}
</style>
