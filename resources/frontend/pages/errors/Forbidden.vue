<template>
  <ErrorLayout>
    <div class="text-center flec flex-col gap-4 mb-2">
      <h1 class="text-9xl font-extrabold text-primary text-shadow-lg">403</h1>
      <h2 class="font-extrabold text-4xl text-primary mt-4">Access Denied</h2>
    </div>
    <p class="w-150">You don’t have permission to access this page. Please contact your administrator if you believe this is an error.</p>
  </ErrorLayout>
</template>

<script setup>
import ErrorLayout from '@/layouts/ErrorLayout.vue'
</script>

<style scoped></style>
