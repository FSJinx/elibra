<template>
  <ErrorLayout>
    <div class="text-center flec flex-col gap-4 mb-2">
      <h1 class="text-9xl font-extrabold text-primary text-shadow-lg">404</h1>
      <h2 class="font-extrabold text-4xl text-primary mt-4">Page Not Found</h2>
    </div>
    <p class="w-150">We couldn’t find the page you were looking for. Check the URL to make sure its correct and try again.</p>
  </ErrorLayout>
</template>

<script setup>
import ErrorLayout from '@/layouts/ErrorLayout.vue'
</script>

<style scoped></style>
