<template>
 <div v-if="popUpModalCreate" class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">

        <div class="absolute inset-0 "   @click="$emit('close')"></div>
        <form class="bg-[#0F1551]  rounded-2xl shadow space-y-4 p-6 w-1/3  pt-15 relative" @click.stop
          @submit.prevent="submitSoftware">
          <!-- Name -->
          <div class="flex justify-between items-center">
            <div class="header w-full flex justify-end ">
              <h2
              class="text-3xl font-bold text-white rounded-lg shadow-md px-2 py-1 text-center mb-6 inline-block pb-2 ">
              Create Software
            </h2>
            </div>
            <div class="back w-1/2 flex justify-end">
              <Link href="/software" class="!text-amber-600 hover:!text-amber-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                clip-rule="evenodd" />
            </svg>
            Back
            </Link>
            </div>
          </div>
          <div>
            <label for="name" class="block text-sm font-medium text-amber-600 mb-2 ml-2">Name</label>
            <input type="text" id="name" name="name" v-model="form.name"
              class="w-full px-4 py-2 text-white border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Enter name" />
            <p v-if="errors.name" class="text-red-600 text-sm mt-1">
              {{ errors.name }}
            </p>
          </div>

          <!-- URL -->
          <div>
            <label for="url" class="block text-sm font-medium text-amber-600 mb-2 ml-2">URL</label>
            <input type="url" id="url" name="url" v-model="form.url"
              class="w-full px-4 py-2 text-white border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="https://example.com" />
            <p v-if="errors.url" class="text-red-600 text-sm mt-1">
              {{ errors.url }}
            </p>
          </div>

          <!-- Is Active -->
          <div class="flex items-center space-x-2">
            <input type="checkbox" id="is_active" name="is_active" v-model="form.is_active"
              class="h-4 w-4 text-white focus:ring-blue-500 border-amber-600 rounded" />
            <label for="is_active" class="text-base text-amber-600 ml-2 ">Active</label>
          </div>

          <!-- Submit -->
          <div>
            <button type="submit" :disabled="processing"
              class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg">
              Submit
            </button>
          </div>
        </form>
      </div>
</template>
<script setup>
import { useForm ,Link} from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  popUpModalCreate: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['close'])

const form = useForm({
  name: '',
  url: '',
  is_active: true
})

const errors = ref({})

function submitSoftware() {
  form.post('/software', {
    onSuccess: () => {
      form.reset()
      emit('close') // close the modal in parent
      router.visit(route('software.index'));
    },
    onError: () => {
      errors.value = form.errors
    }
  })
}
const processing = form.processing
</script>
