<template>
  <div class="h-full w-full skeleton"></div>
</template>

<script setup lang="ts"></script>

<style scoped>
.skeleton {
  /* Kulay ng background (base gray) */
  background-color: var(--color-tertiary);

  /* Shimmer gradient overlay */
  background-image: linear-gradient(280deg, var(--color-secondary) 0%, var(--color-tertiary) 50%, var(--color-secondary) 100%);
  background-size: 200% 100%;
  background-repeat: no-repeat;

  /* Smooth animation */
  animation: skeleton-shimmer 1.5s linear infinite;
}

@keyframes skeleton-shimmer {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}
</style>
