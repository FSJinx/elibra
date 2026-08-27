<template>
  <div class="p-5">
    <DashboardCards />
  </div>
</template>

<script setup lang="ts">
import DashboardCards from '@/app/admin/dashboard/components/DashboardCards.vue'

const auth = authStore()
const route = useRouter()
</script>

<style scoped></style>
