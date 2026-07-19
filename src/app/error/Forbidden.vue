<template>
  <ErrorLayout>
    <template #code>403</template>
    <template #header>Access Denied</template>
    <template #message>You're trying to access a page without the proper credentials access this page. Please <Chip class="cursor-pointer" @click="router.push({ name: 'login' })">Login</Chip> and try again. If you think this is an error, please contact your administrator.</template>
  </ErrorLayout>
</template>

<script setup lang="ts">
import ErrorLayout from '../../layouts/ErrorLayout.vue'
</script>

<style scoped></style>
