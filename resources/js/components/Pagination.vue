<template>
 <div class="flex justify-end w-full mt-4 space-x-4 text-sm md:text-base gap-2">
        <button :disabled="pagination.current_page === 1" @click="$emit('change-page',pagination.current_page - 1)"
          :class="['px-2 py-1 border rounded hover:bg-blue-400 hover:!text-white disabled:opacity-50',{'cursor-not-allowed': pagination.current_page === 1 }]">
          Prev
        </button>

        <button v-for="page in pagination.last_page" :key="page" @click="$emit('change-page',page)"
          class="px-2 py-1 border rounded hover:bg-blue-400 hover:!text-white" :class="{
            'bg-blue-500 text-white hover:!text-white': page === pagination.current_page
          }">
          {{ page }}
        </button>

        <button :disabled="pagination.current_page === pagination.last_page"
          @click="$emit('change-page',pagination.current_page + 1)"
          :class="['px-2 py-1 border rounded hover:bg-blue-400 hover:!text-white  disabled:opacity-50',{'cursor-not-allowed': pagination.current_page === pagination.last_page }]">
          Next
        </button>
      </div>
</template>
<script setup lang="ts">
import {onMounted} from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    pagination:{
        type:Object,
        required:true,
    }
})
onMounted(()=>{
    console.log(props.pagination);
})

</script>