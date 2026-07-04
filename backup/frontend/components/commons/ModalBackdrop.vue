<template>
  <transition name="backdrop">
    <div v-if="backdrop.show" id="backdrop" class="fixed inset-0 z-40 w-full bg-black/50" @click="backdrop.close" />
  </transition>
</template>

<script setup>
import { useBackdrop } from '@/stores/backdrop'

const backdrop = useBackdrop()
</script>

<style scoped>
.backdrop-enter-active,
.backdrop-leave-active {
  transition: opacity 0.2s ease;
}

.backdrop-enter-from,
.backdrop-leave-to {
  opacity: 0;
}

.backdrop-enter-to,
.backdrop-leave-from {
  opacity: 1;
}
</style>
