<template>
  <div class="">
    <DashboardCards />

    <LogoLoader />

    <div class="h-screen bg-danger"></div>
  </div>
</template>

<script setup lang="ts">
import DashboardCards from '@/app/admin/dashboard/components/DashboardCards.vue'
import LogoLoader from '@/components/brand/LogoLoader.vue'

const auth = authStore()
</script>

<style scoped></style>
