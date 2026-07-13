<template>
  <ErrorLayout>
    <div class="space-y-5 place-items-center">
      <h1 class="text-4xl font-extrabold tracking-wide text-red-500 uppercase">Error 404</h1>
      <h1 class="text-9xl font-extrabold text-primary uppercase">not found</h1>

      <p class="max-w-6xl text-xl text-center text-slate-600">The page you're looking for is either under going maintenance, under construction, moved, or not existing. Please contact your administrator if you think this is a mistake.</p>
    </div>
  </ErrorLayout>
</template>

<script setup lang="ts">
import ErrorLayout from '../../layouts/ErrorLayout.vue'
</script>

<style scoped></style>
