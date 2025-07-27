    <template>
  <MainLayout>
    <div class="w-1/2 mx-auto p-6 bg-white rounded-2xl shadow space-y-6 mt-10">
      <h1 class="text-center text-xl font-semibold">View Package</h1>

      <div>
        <strong>Name:</strong>
        <p class="text-gray-700">{{ ourPackage.name }}</p>
      </div>

      <div>
        <strong>Price:</strong>
        <p class="text-gray-700">{{ ourPackage.is_free ? 'Free' : `${ourPackage.price} Tk` }}</p>
      </div>

      <div>
        <strong>Duration:</strong>
        <p class="text-gray-700">{{ ourPackage.duration }} {{ ourPackage.duration_type }}</p>
      </div>

      <div>
        <strong>Description:</strong>
        <p class="text-gray-700 whitespace-pre-line">{{ ourPackage.description || 'N/A' }}</p>
      </div>

      <div>
        <strong>Softwares Included:</strong>
        <ul class="list-disc list-inside text-gray-700 mt-2" v-if="selectedSoftwares.length">
          <li v-for="software in selectedSoftwares" :key="software.id">
            {{ software.name }}
          </li>
        </ul>
        <p v-else class="text-gray-500">No software assigned to this package.</p>
      </div>
    </div>
  </MainLayout>
</template>
<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, watch, defineProps, onMounted } from 'vue'

const page = usePage()

const props = defineProps({
  popUpModalEdit: Boolean,
  paymentData: {
    type:Object,
  },


})

const emit = defineEmits(['close'])

const form = useForm({
  user_id: props.paymentData.user_id || '',
  package_id: props.paymentData.subscription?.package?.id || '',
  payment_method: props.paymentData.payment_method || '',
  payment_status: props.paymentData.payment_status || '',
  amount: props.paymentData.amount || '',
  duration: props.paymentData.duration || '',
  duration_type: props.paymentData.duration_type || '',
})





</script>
