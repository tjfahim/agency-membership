<template>
  <MainLayout>
    <!-- Success message -->
    <div v-if="showMessage" class="alert alert-success mb-4">
      {{ flash?.success }}
    </div>
    <!-- Empty warning -->
    <div v-if="showEmpty" class="alert alert-warning mb-4">
      {{ emptyMessage }}
    </div>

    <div class="p-6 relative">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Software List</h1>
        <Button @click="toggleCreateModal"
          class="block px-4 py-2 ml-5 !text-slate-200 !no-underline rounded bg-amber-600 hover:bg-amber-700 text-lg">
          Create Software
        </Button>
      </div>

      <!-- Create, Edit, View Modals -->
      <createSoftwareComponent :popUpModalCreate="popUpModalCreate" @close="handleCreateClose" />
      <editSoftwareComponent :popUpModalUpdate="popUpModalUpdate" @close="handleEditClose" :updateForm="updateForm" />
      <viewSoftwareComponent :popUpModalView="popUpModalView" @close="popUpModalView=false" :updateForm="updateForm" />

      <!-- Software DataTable -->
      <vue-good-table
        :columns="columns"
        :rows="filteredSoftware"
        :search-options="{ enabled: true, placeholder: 'Search software...' }"
        :pagination-options="{ enabled: true, perPage: 10, perPageDropdown: [5,10,20,50] }"
        :sort-options="{ enabled: true }"
        :styleClass="'vgt-table '"
      >
        <template #table-row="{ row, column }">
          <td v-if="column.field === 'actions'" class="text-center  py-2">
            <div class="flex justify-center space-x-4 ">
              <button @click="viewSoftware(row)" title="View"><i class="las la-eye text-green-500 text-xl px-1"></i></button>
              <button @click="editSoftware(row)" title="Edit"><i class="las la-edit text-yellow-500 text-xl px-1"></i></button>
              <button @click="deleteSoftware(row.id)" title="Delete"><i class="las la-trash text-red-500 text-xl px-1"></i></button>
            </div>
          </td>
          <td v-else>
            {{ row[column.field] }}
          </td>
        </template>
      </vue-good-table>
    </div>
  </MainLayout>
</template>

<script setup>
import { defineProps, ref, computed, watch, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'

import MainLayout from '@/components/layouts/MainLayout.vue'
import createSoftwareComponent from '../../components/Software/create.vue'
import editSoftwareComponent from '../../components/Software/edit.vue'
import viewSoftwareComponent from '../../components/Software/view.vue'

const props = defineProps({
  softwares: Object,
  flash: Object,
  pagination: Object,
})

const popUpModalCreate = ref(false)
const popUpModalUpdate = ref(false)
const popUpModalView = ref(false)
const updateForm = ref({
  id: '',
  name: '',
  url: '',
  is_active: true,
})

const showMessage = ref(false)
const showEmpty = ref(false)
const emptyMessage = ref('')
const isDelete = ref(false)
const deletedId = ref(null)

const toggleCreateModal = () => popUpModalCreate.value = true
const handleCreateClose = () => setTimeout(() => (popUpModalCreate.value = false), 0)
const handleEditClose = () => setTimeout(() => (popUpModalUpdate.value = false), 0)

const viewSoftware = (software) => {
  updateForm.value = { ...software }
  popUpModalView.value = true
}

const editSoftware = (software) => {
  updateForm.value = { ...software }
  popUpModalUpdate.value = true
}

const deleteSoftware = (id) => {
  if (confirm('Are you sure you want to delete this software?')) {
    isDelete.value = true
    deletedId.value = id
    router.delete(`/software/${id}`, {
      onSuccess: () => console.log('Deleted successfully'),
    })
  }
}

const filteredSoftware = computed(() => {
  let data = props.softwares?.data || []
  return isDelete.value && deletedId.value !== null
    ? data.filter(item => item.id !== deletedId.value)
    : data
})

// Table columns
const columns = [
  { label: 'Name', field: 'name', sortable: true },
  { label: 'URL', field: 'url', sortable: true },
  {
    label: 'Active',
    field: 'is_active',
    sortable: true,
    formatFn: (value) => value ? 'Yes' : 'No',
  },
  { label: 'Actions', field: 'actions', sortable: false },
]

// Flash messages
const showFlash = () => {
  showMessage.value = true
  setTimeout(() => (showMessage.value = false), 3000)
}

watch(() => props.flash?.success, (newVal) => {
  if (newVal) showFlash()
})

watch(() => props.flash?.software_empty, (newVal) => {
  if (newVal) {
    emptyMessage.value = newVal
    showEmpty.value = true
    setTimeout(() => (showEmpty.value = false), 3000)
  }
})

onMounted(() => {
  if (props.flash?.success) showFlash()
})
</script>

<style scoped>

.vgt-table.bordered th {
  border: 1px solid #57514fa6;
}
table.vgt-table td {
  padding: 0.2em;
}
</style>
