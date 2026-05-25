<template>
  <div class="flex flex-col gap-5">
    <div class="mb-5">
      <h1 class="text-3xl font-bold">Overview</h1>
      <p class="text-neutral-400">This page displays the overall performance of all the campus.</p>
    </div>

    <DashboardCards />
    <DashboardCards />
    <DashboardCards />
    <DashboardCards />
    <DashboardCards />
    <DashboardCards />
    <DashboardCards />
  </div>
</template>

<script setup>
import DashboardCards from './dashboard/DashboardCards.vue'
</script>

<style scoped></style>
