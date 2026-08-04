<template>
  <tbody>
    <Tload v-if="loading" :columns="columns"/>
    <slot v-else />
  </tbody>
</template>

<script setup lang="ts">
defineProps<{ loading: boolean, columns:number }>()
</script>
