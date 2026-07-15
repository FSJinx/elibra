<template>
  <ErrorLayout>
    <div class="space-y-5 place-items-center">
      <h1 class="text-4xl font-extrabold tracking-wide text-red-500 uppercase">Error 403</h1>
      <h1 class="text-9xl font-extrabold text-primary uppercase">forbidden</h1>

      <p class="max-w-6xl text-xl text-center text-slate-600">You don't have permission to access this page. Please contact your administrator if you believe this is an error.</p>
    </div>
  </ErrorLayout>
</template>

<script setup lang="ts">
import ErrorLayout from '../../layouts/ErrorLayout.vue'
</script>

<style scoped></style>
