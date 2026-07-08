<template>
  <aside :class="['flex flex-col h-screen bg-white relative border-r border-slate-300 whitespace-nowrap text-nowrap flex-nowrap overflow-hidden transition-all duration-300', isOpen ? 'w-75' : 'w-0']">
    <div class="relative flex flex-col min-w-75 overflow-hidden">
      <SideBody />

      <!-- <SideFooter /> -->
    </div>
  </aside>
</template>

<script setup lang="ts">
import SideBody from './SideBody.vue'

const props = withDefaults(
  defineProps<{
    isOpen?: boolean
  }>(),
  { isOpen: false },
)
</script>

<style scoped></style>
