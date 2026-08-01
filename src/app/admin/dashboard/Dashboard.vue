<template>
  <DashboardCards />

  <LogoLoader />

  <div class="inset-0 fixed flex bg-backdrop h-dvh w-full">
    <div class="flex flex-col justify-center items-center bg-background rounded-2xl m-auto w-125 p-5 text-center">
      <!-- Logo -->
      <div class="relative flex justify-center items-center size-25 bg-red-50 text-danger rounded-full m-5">
        <Logo width="40" />
      </div>

      <!-- Error Code -->
      <div class="relative flex justify-center items-center max-w-max text-lg p-2 font-bold tracking-wide text-danger uppercase">
        Error

        <div class="h-0.5 w-[50%] min-w-10 absolute bottom-0 bg-danger rounded-full"></div>
      </div>

      <div class="pt-5 p-10 space-y-2">
        <!-- Error Title -->
        <h1 class="text-2xl font-semibold capitalize">Error</h1>

        <!-- Error Message -->
        <p class="text-muted">The error message will appear here.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import DashboardCards from '@/app/admin/dashboard/components/DashboardCards.vue'
import LogoLoader from '@/components/brand/LogoLoader.vue'

const auth = authStore()
</script>

<style scoped></style>
