<template>
  <MainLayout>
    <div v-if="showMessage" class="alert alert-success mb-4">
      {{ flash?.success }}
    </div>

    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Subscription List</h1>

      <vue-good-table
        :columns="columns"
        :rows="filteredSubscriptions"
        :search-options="{ enabled: true, placeholder: 'Search subscriptions...' }"
        :pagination-options="{
          enabled: true,
          perPage: 10,
          perPageDropdown: [5, 10, 20, 50],
          dropdownAllowAll: false
        }"
        :sort-options="{ enabled: true }"
        :styleClass="'vgt-table bordered'"
      >
        <template #table-row="{ row, column }">
          <td v-if="column.field === 'payment_status'" class="px-4 py-2">
            <span
              v-if="row.package.is_free !== 1"
              :class="['px-2 py-1 rounded text-white', row.payment_status === 'Paid' ? 'bg-green-500' : 'bg-red-500']"
            >
              {{ row.payment_status }}
            </span>
            <span v-else class="italic text-gray-500">Free</span>
          </td>

          <td v-else-if="column.field === 'actions'" class="text-center py-2">
            <div class="flex justify-center space-x-4">
              <Link
                :href="`/subscription/${row.id}`"
                class="text-blue-600 hover:underline"
                title="View Subscription"
              >
                <i class="las la-eye text-green-500 text-xl"></i>
              </Link>

              <Link
                :href="`/subscription/${row.id}/edit`"
                class="text-yellow-600 hover:underline"
                title="Edit Subscription"
              >
                <i class="las la-edit text-xl"></i>
              </Link>

              <button
                @click="deleteSubscription(row.id)"
                class="text-red-600 hover:underline"
                title="Delete Subscription"
              >
                <i class="las la-trash text-xl"></i>
              </button>
            </div>
          </td>

          <td v-else>
            {{ row[column.field] }}
          </td>
        </template>
      </vue-good-table>

      <Pagination
        v-if="subscriptions?.data?.length > 0"
        :pagination="pagination"
        @change-page="changePage"
      />
    </div>
  </MainLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import MainLayout from '@/components/layouts/MainLayout.vue'
import Pagination from '../../components/Pagination.vue'
import { defineProps, ref, computed, onMounted, watch } from 'vue'
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'
import dayjs from 'dayjs'

const props = defineProps({
  subscriptions: Object,
  flash: Object,
  pagination: Object,
})

const showMessage = ref(false)
const isDelete = ref(false)
const deletedId = ref(null)

function deleteSubscription(id) {
  if (confirm('Are you sure you want to delete this subscription?')) {
    isDelete.value = true
    deletedId.value = id
    router.delete(`/subscription/${id}`, {
      onSuccess: () => {
        console.log('Deleted successfully')
         router.visit(route('subscription.index'));
      },
    })
  }
}

const filteredSubscriptions = computed(() => {
  let data = props.subscriptions?.data || []

  // Remove deleted item from displayed data
  if (isDelete.value && deletedId.value !== null) {
    data = data.filter((sub) => sub.id !== deletedId.value)
  }

  // Flatten nested fields for easier display and sorting
  return data.map((sub) => ({
    ...sub,
    user_name: sub.user?.name ?? 'N/A',
    package_name: sub.package?.name ?? 'N/A',
    assigned_by_name: sub.assigned_by?.name ?? 'N/A',
    start_at : sub.start_at ??'N/A',
    end_at : sub.end_at ??'N/A',
  }))
})

const columns = [
  { label: 'User', field: 'user_name', sortable: true },
  { label: 'Package', field: 'package_name', sortable: true },
  { label: 'Start Date', field: 'start_at', sortable: true },
  { label: 'End Date', field: 'end_at', sortable: true },
  { label: 'Payment Status', field: 'payment_status', sortable: true },
  { label: 'Assigned By', field: 'assigned_by_name', sortable: true },
  { label: 'Actions', field: 'actions', sortable: false },
]

onMounted(() => {
  if (props.flash?.success) {
    showFlash()
  }
})

const showFlash = () => {
  showMessage.value = true
  setTimeout(() => {
    showMessage.value = false
  }, 3000)
}

watch(() => props.flash?.success, (newVal) => {
  if (newVal) {
    showFlash()
  }
})

const changePage = (page) => {
  router.get(route('subscription.index', { page }, { preserveState: true }))
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
  padding: 0.1em !important;
}
</style>
