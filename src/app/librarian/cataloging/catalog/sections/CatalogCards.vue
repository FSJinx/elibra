<template>
  <div class="grid grid-cols-5 gap-3">
    <template v-for="stat in stats">
      <StatCard :label="stat.label" :value="stat.value" :icon="stat.icon" />
    </template>
  </div>
</template>

<script setup lang="ts">
const stats = ref([
  { label: 'Total Items', value: null },
  { label: 'Total Academics', value: 6542.74523, icon: 'mortarboard-fill' },
  { label: 'Total Books', value: 2312, icon: 'book-fill' },
  { label: 'Total Serials', value: 'Currently none', icon: 'bookmark-fill' },
])
</script>

<style scoped></style>
