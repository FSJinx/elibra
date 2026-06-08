<template>
  <div class="bubble-wrapper flex items-center gap-1 w-max h-6" role="status" aria-label="loading">
    <div class="bubble" :class="[color]"></div>
    <div class="bubble" :class="[color]"></div>
    <div class="bubble" :class="[color]"></div>
    <div class="bubble" :class="[color]"></div>
    <div class="bubble" :class="[color]"></div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(
  defineProps<{
    color?: 'primary' | 'default'
  }>(),
  {
    color: 'default',
  },
)
const color = computed(() => {
  const colors = {
    default: 'bg-gray-200',
    primary: 'bg-primary',
  }

  return colors[props.color]
})
</script>

<style scoped>
.bubble {
  width: 10px;

  animation: bubble 1.5s linear infinite;
}

.bubble:nth-child(2) {
  animation-delay: 0.2s;
}

.bubble:nth-child(3) {
  animation-delay: 0.4s;
}

.bubble:nth-child(4) {
  animation-delay: 0.6s;
}

.bubble:nth-child(5) {
  animation-delay: 0.8s;
}

@keyframes bubble {
  0%,
  50%,
  100% {
    height: 0;
  }
  25% {
    height: 20px;
  }
}
</style>
