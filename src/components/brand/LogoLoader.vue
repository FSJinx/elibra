<template>
  <div class="logo-loader-wrapper text-primary size-full">
    <Logo class="logo-loader text-[3em]" />
    <p>{{ message }}</p>
  </div>
</template>

<script setup lang="ts">
interface Props {
  message?: string
}
const props = withDefaults(defineProps<Props>(), {
  message: 'Please wait while we prepare your tools...',
})
</script>

<style scoped>
.logo-loader-wrapper {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.logo-loader {
  /* Step 1: Slide Down (minsan lang sa umpisa) */
  /* Step 2: Pulse (infinite loop pagkatapos mag-slide down) */
  animation:
    slideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards,
    pulse 1s ease-in-out 0.6s infinite alternate;

  will-change: transform, opacity;
}

/* 1. Slide down effect galing sa taas */
@keyframes slideDown {
  0% {
    opacity: 0;
    transform: translateY(-30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* 2. Continuous Pulse effect */
@keyframes pulse {
  0%,
  100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.12);
    opacity: 0.65;
  }
}
</style>
