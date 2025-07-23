<template>
  <MainLayout>
    <div v-if="showMessage" class="alert alert-success">
      {{ flash?.success }}
    </div>
    
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">User List</h1>
      <!--table-->
      <table class="w-full border text-left">
        <thead>
          <tr class="bg-gray-200">
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Created</th>
            <th class="p-2 text-center">Action</th>

          </tr>
        </thead>
        <tbody v-if="users?.data?.length>0">
          <tr v-for="user in filteredUsers" :key="user.id" class="border-b">
            <td class="p-2">{{ user.name }}</td>
            <td class="p-2">{{ user.email }}</td>
            <td class="p-2">{{ user.created_at_human }}</td>
            <div class="flex gap-4 p-2  justify-center items-center ">
               <Link :href="`/users/${user.id}/edit`" class="text-yellow-600 hover:underline"><i
                    class="las la-edit md:text-md lg:text-lg xl:text-2xl  "></i></Link>
                <button @click="deleteUser(user.id)" class="text-red-600 hover:underline">
                    <i class="las la-trash md:text-md lg:text-lg xl:text-2xl "></i>
                </button>
            </div>

          </tr>
        </tbody>
      </table>
      <!-- Pagination Controls -->
       <Pagination v-if="users?.data?.length>0" :pagination = "pagination" @change-page="changePage"></Pagination>
      <!--End Pagination-->
    </div>
  </MainLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import MainLayout from '@/components/layouts/MainLayout.vue';
import Pagination from '../../components/Pagination.vue';
import { defineProps, ref, computed, onMounted, watch } from 'vue'
const props = defineProps({
  users: Array,
  pagination: Array,
  flash: String,
})

const is_delete = ref(false);
const deletedId = ref(null);
const showMessage = ref(false);

function changePage(page) {
  router.get(route('users.index'), { page }, { preserveState: true })
}

function deleteUser(id) {
  if (confirm('Are you sure you want to delete this package?')) {
    is_delete.value = true;
    deletedId.value = id;
    router.delete(`/users/${id}`, {
      onSuccess: () => {
        console.log('deleted Successfuly');
      }
    });

  }
} 

const filteredUsers = computed(() => {
  if (is_delete.value === true && deletedId.value !== null) {
    return props.users.data.filter((user) => user.id != deletedId.value);
  }
  return props.users.data;
});

onMounted(() => {
  if (props.flash?.success) {
    showFlash();
  }
  console.log(props.users);
});

const showFlash = () => {
  showMessage.value = true;
  setTimeout(() => {
    showMessage.value = false;
  }, 3000);
}

watch(() => props.flash?.success, (newVal) => {
  if (newVal) {
    showFlash();
  }
});

</script>
