<template>
  <div class="flex flex-col gap-3 w-full border-b border-gray-200 p-5">
    <h1 class="text-2xl font-bold">Popup Alerts</h1>
    <div class="grid grid-cols-2">
      <p class="my-auto">Confirm Popup</p>
      <div class="flex flex-wrap items-center gap-2">
        <Button label="Confirm" color="green" type="soft" @click="elpop.confirm('You successfully deleted your feelings.')" />
      </div>
    </div>
    <div class="grid grid-cols-2">
      <p class="my-auto">Loading Popup</p>
      <div class="flex flex-wrap items-center gap-2">
        <Button label="Loading" color="blue" type="soft" @click="elpop.load()" />
      </div>
    </div>
    <div class="grid grid-cols-2">
      <p class="my-auto">Success Popup</p>
      <div class="flex flex-wrap items-center gap-2">
        <Button label="Success" color="green" type="soft" @click="elpop.showSuccess('You successfully deleted your feelings.')" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

import elpop from '@/plugins/elpop'
import Button from '@/components/buttons/Button.vue'
</script>
