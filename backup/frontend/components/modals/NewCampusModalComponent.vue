<template>
  <ModalLayout ref="campus">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Add New Campus</h3>

    <div class="flex flex-col gap-3 mb-6">
      <label class="text-sm font-medium text-gray-600">Campus Name</label>
      <input type="text" class="border p-2 rounded-sm focus:outline-primary" placeholder="e.g., Main Campus" />
    </div>

    <div class="flex justify-end gap-2">
      <button @click="close" class="px-4 py-2 border rounded-sm text-gray-600 hover:bg-gray-50">Cancel</button>
      <button @click="submit" class="px-4 py-2 bg-blue-600 text-white rounded-sm hover:bg-blue-700">Save Campus</button>
    </div>
  </ModalLayout>
</template>

<script setup>
import { ref } from 'vue'
import ModalLayout from '@/layouts/ModalLayout.vue'

const campus = ref(null)

defineExpose({
  open: () => campus.value?.open(),
})
</script>
