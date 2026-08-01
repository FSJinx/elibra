<template>
  <div class="flex h-screen w-full bg-linear-to-b from-slate-50 to-primary-soft/50 overflow-hidden">
    <!-- Sidebar -->
    <ManagementSidebar />

    <!-- Body -->
    <ManagementBody />
  </div>
</template>

<script setup lang="ts">
import ManagementBody from '@/layouts/management_new/ManagementBody.vue'
import ManagementSidebar from '@/layouts/management_new/ManagementSidebar.vue'
</script>

<style scoped></style>
