<template>
    <div v-if="popUpModalView" class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">

        <div class="absolute inset-0 "  @click="$emit('close')"></div>
        <form class="bg-[#0F1551]  rounded-2xl shadow space-y-4 p-6 w-1/2  relative" @click.stop>
            <!-- Name -->
            <div class="flex justify-between items-center">
                <div class="back w-full flex justify-end">
                    <Link href="/package" class="!text-amber-600 hover:!text-amber-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back
                    </Link>
                </div>
            </div>
        <!--View Package-->
            <div class="w-full mx-auto p-6  rounded-2xl shadow space-y-4 mt-2">
                <h3 class="text-center my-1 text-md text-white">Our Package</h3>
                <div>
                    <label class="block text-lg font-medium text-amber-600 mb-1">Name:</label>
                    <span class="text-white text-lg font-bold ml-1"> {{ companyPackage.name }}</span>
                </div>

                <div>
                    <label class="block text-lg font-medium text-amber-600 mb-1">Duration Type:</label>
                    <span class="text-white text-lg font-bold ml-1"> {{ companyPackage.duration_type }}</span>
                </div>
                <div>
                 <label  class="block text-lg font-medium text-amber-600 mb-1">Price:</label>
                    <span class="text-white text-lg font-bold ml-1"> {{ companyPackage.price }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    
                    <label for="is_free" class="text-lg font-bold text-amber-700">Free:</label>
                    <span v-if="companyPackage.is_free" class="text-lg text-lime-600 ml-2">Yes</span><span class="text-lg text-red-600 ml-2" v-else>No</span>
                </div>
                 <h3 class="text-center my-2 text-white">Our Softwares</h3>
                <div class="flex flex-wrap gap-2 my-4" v-if="companySoftwares.length">
                    <span v-for="software in companySoftwares" :key="software.id"
                        class="inline-flex items-center px-3 py-1 rounded-full text-lg font-medium bg-blue-100 text-amber-600 transition duration-200">
                        {{ software.name }}
                    </span>
                </div>
            </div>
        </form>

    </div>
</template>
<script setup>
import { useForm,Link } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps({
    popUpModalView: {
        type: Boolean,
        required: true
    },
    companyPackage:Object,
    companySoftwares:{
        type:Array,
        default: ()=>[],
    }
})

const emit = defineEmits(['close'])


const errors = ref({})

watch(props.companyPackage,(newVal)=>{
    alert(newVal);
})
watch(props.companySoftwares,(newVal)=>{
    alert(newVal);
})
const convertDuration= (duration, type)=>{
    switch(type){
        case 'daily':
             return duration+" day";
        case 'weekly':
            return duration + " week";
            case 'monthly':
                return duration+ " month";
                default:
                    return duration+ " year";   
    }
}
</script>
