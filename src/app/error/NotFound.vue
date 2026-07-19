<template>
  <ErrorLayout>
    <template #code>404</template>
    <template #header>This page isn't available right now</template>
    <template #message>The page you're looking for is either under going maintenance, under construction, moved, or not existing. If you think this is an error, please contact your administrator.</template>
  </ErrorLayout>
</template>

<script setup lang="ts">
import ErrorLayout from '../../layouts/ErrorLayout.vue'
</script>

<style scoped></style>
