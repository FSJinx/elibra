<template>
  <div class="flex flex-col items-center justify-center h-full w-full">
    <div class="popup-graphic"></div>
    <div class="popup-content flex flex-col items-center justify-center gap-2 mb-2 mt-4">
      <h2 class="font-bold text-3xl">{{ title }}</h2>
      <span>{{ message }}</span>
    </div>
    <div class="popup-buttons flex items-center justify-center gap-2">
      <Button class="mt-5" label="Confirm" color="green" type="solid" @click="popup.close" />
    </div>
  </div>
</template>

<script setup>
import { usePopup } from '@/stores/popup.js'
import Button from '../buttons/Button.vue'

const popup = usePopup()

defineProps({
  title: String,
  message: {
    type: String,
    default: 'Successful!',
  },
})
</script>
