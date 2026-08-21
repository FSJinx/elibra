<template>
  {{ auth.getFormalName }}
</template>

<script setup lang="ts">
const auth = authStore()

console.log(auth.user)
</script>

<style scoped></style>
