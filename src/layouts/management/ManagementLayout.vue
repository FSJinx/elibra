<template>
  <div class="flex flex-col h-screen bg-secondary items-start overflow-hidden">
    <ManagementHeader />
    <ManagementBreadcrumb />
    <div class="relative flex flex-1 w-full overflow-hidden">
      <!-- Sidebar -->
      <ManagementSidebar />

      <!-- Body -->
      <ManagementBody />
    </div>
  </div>
</template>

<script setup lang="ts">
import ManagementBody from '@/layouts/management/ManagementBody.vue'
import ManagementBreadcrumb from '@/layouts/management/ManagementBreadcrumb.vue';
import ManagementHeader from '@/layouts/management/ManagementHeader.vue'
import ManagementSidebar from '@/layouts/management/ManagementSidebar.vue'
</script>

<style scoped></style>
