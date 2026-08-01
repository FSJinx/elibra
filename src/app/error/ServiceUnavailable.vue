<template>
  <ErrorLayout>
    <template #code>503</template>
    <template #header>Service Unavailable</template>
    <template #message>You're trying to access a page that is going under maintenance. Try to access the page again after the maintenance. If you think this is an error, contact your administrator.</template>
  </ErrorLayout>
</template>

<script setup lang="ts">
import ErrorLayout from '@/layouts/ErrorLayout.vue'
</script>

<style scoped></style>
