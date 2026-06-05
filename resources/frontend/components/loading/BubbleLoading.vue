<template>
  <div class="bubble-wrapper flex gap-0 w-max" role="status" aria-label="loading">
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
  </div>
</template>

<script setup></script>

<style scoped>
.bubble {
  height: 15px;
  width: 15px;
  border-radius: 50%;
  background-color: var(--color-primary);

  animation: bubble 1s linear infinite;
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
    transform: scale(0);
  }
  25% {
    transform: scale(1);
  }
}
</style>
