<template>
  <div class="relative flex h-screen w-full p-4 pr-5 bg-secondary overflow-hidden">
    <!-- Sidebar -->
    <ManagementSidebar />

    <!-- Body -->
    <ManagementBody />
  </div>
</template>

<script setup lang="ts">
import ManagementBody from '@/layouts/management/ManagementBody.vue'
import ManagementSidebar from '@/layouts/management/ManagementSidebar.vue'
</script>

<style scoped></style>
