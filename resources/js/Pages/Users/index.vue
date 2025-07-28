<template>
  <MainLayout>
    <!-- Flash Messages -->
    <div v-if="showMessage" class="alert alert-success mb-4">
      {{ flash?.success }}
    </div>
    <div v-if="flash?.error" class="text-red-500 mb-4">
      {{ flash?.error }}
    </div>

    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">User List</h1>

      <button
        @click="toggleCreate"
        class="block px-4 py-2 mb-4 text-white bg-amber-600 hover:bg-amber-700 rounded"
      >
        Create User
      </button>

      <!-- Modals -->
      <CreateUser :createUserModal="createUserModal" @close="closeCreateModal" />
      <EditUser :editUserModal="editUserModal" :User="User" @close="closeEditModal" />

      <!-- Datatable -->
      <vue-good-table
        :columns="columns"
        :rows="filteredUsers"
        :search-options="{ enabled: true, placeholder: 'Search users...' }"
        :pagination-options="{
          enabled: true,
          perPage: 10,
          perPageDropdown: [5, 10, 20],
        }"
        :styleClass="'vgt-table bordered'"
      >
        <template #table-row="{ row, column }">
          <td v-if="column.field === 'actions'" class="text-center">
            <div class="flex justify-center gap-4">
              <button
                @click="togglePassword(row.id)"
                class="text-orange-500 hover:underline"
              >
                <i class="fa fa-key mr-1"></i>Set Password
              </button>
              <button
                @click="toggleEdit(row)"
                class="text-yellow-600 hover:underline"
              >
                <i class="las la-edit text-xl"></i>
              </button>
              <button
                @click="deleteUser(row.id)"
                class="text-red-600 hover:underline"
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
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/components/layouts/MainLayout.vue'
import { ref, computed, defineProps, onMounted, watch } from 'vue'
import CreateUser from '@/components/User/create.vue'
import EditUser from '@/components/User/edit.vue'
import { router } from '@inertiajs/vue3'
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'
import axios from 'axios'
import { route } from 'ziggy-js'

const props = defineProps({
  users: Object,
  flash: Object
})

const createUserModal = ref(false)
const editUserModal = ref(false)
const showMessage = ref(false)
const showError = ref(false)

const User = ref({})
const isDelete = ref(false)
const deletedId = ref(null)

// Columns
const columns = [
  { label: 'Name', field: 'name', sortable: true },
  { label: 'Email', field: 'email', sortable: true },
  { label: 'Created', field: 'created_at_formatted', sortable: true },
  { label: 'Actions', field: 'actions', sortable: false }
]

// Computed filtered users
const filteredUsers = computed(() => {
  const data = props.users?.data || []
  return isDelete.value && deletedId.value !== null
    ? data.filter(u => u.id !== deletedId.value)
    : data
})

// Modal controls
const toggleCreate = () => {
  createUserModal.value = true
}
const closeCreateModal = () => {
  setTimeout(() => (createUserModal.value = false), 0)
}
const toggleEdit = (user) => {
  User.value = user
  editUserModal.value = true
}
const closeEditModal = () => {
  setTimeout(() => (editUserModal.value = false), 0)
}
const togglePassword = async (id) => {
  const response = await axios.get(`/users/${id}`)
  User.value = response.data
  router.get(route('default.password', User.value))
}

// Delete
const deleteUser = (id) => {
  if (confirm('Are you sure you want to delete this user?')) {
    isDelete.value = true
    deletedId.value = id
    router.delete(`/users/${id}`, {
      onSuccess: () => {
        console.log('Deleted successfully')
      }
    })
  }
}

// Flash messages
const showFlash = () => {
  showMessage.value = true
  setTimeout(() => {
    showMessage.value = false
  }, 3000)
}
const showErrorFlash = () => {
  showError.value = true
  setTimeout(() => {
    showError.value = false
  }, 3000)
}

// Initial mount
onMounted(() => {
  if (props.flash?.success) showFlash()
})

// Watch flash props
watch(() => props.flash?.success, (val) => {
  if (val) showFlash()
})
watch(() => props.flash?.error, (val) => {
  if (val) showErrorFlash()
})
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
  padding: 0.2em;
}
</style>
