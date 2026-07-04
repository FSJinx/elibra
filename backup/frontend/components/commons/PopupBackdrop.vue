<template>
  <transition name="popup">
    <div v-if="popup.show" class="fixed inset-0 z-40 flex items-center justify-center bg-black/50">
      <div class="absolute inset-0" @click="popup.close" />

      <div class="popup-card flex items-center justify-center relative z-50 rounded-md bg-white text-center p-10 w-125">
        <component :is="popup.component" v-bind="popup.props" />
      </div>
    </div>
  </transition>
</template>

<script setup>
import { usePopup } from '@/stores/popup'

const popup = usePopup()
</script>

<style scoped>
.popup-enter-active,
.popup-leave-active {
  transition: opacity 0.2s ease;
}

.popup-leave-active {
  transition-delay: 0.05s;
}

.popup-enter-from,
.popup-leave-to {
  opacity: 0;
}

.popup-enter-active .popup-card,
.popup-leave-active .popup-card {
  transition:
    transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
    opacity 0.2s ease;
}

.popup-enter-from .popup-card,
.popup-leave-to .popup-card {
  opacity: 0;
  transform: scale(0.5);
}
</style>
