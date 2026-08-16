<template>
  <div class="mb-5 flex items-start">
    <div class="space-y-1 mt-2">
      <p class="text-primary text-md uppercase tracking-wide font-medium text-shadow-md text-shadow-primary/15">Good day, {{ auth.user?.first_name }}!👋</p>
      <h1 class="text-2xl font-semibold">{{ $route.meta.title ?? 'Untitled' }}</h1>
      <p class="text-sm text-foreground-secondary">{{ $route.meta.description ?? `This is you're today's preview for ${$route.meta.title}.` }}</p>
    </div>
    <div class="flex items-end gap-1.5 ml-auto">
      <ManagementSearchButton />
      <Button variant="default" icon="bell"></Button>
    </div>
  </div>

  <div class="">
    <DashboardCards />

    <LogoLoader />

    <div class="h-screen bg-danger"></div>
  </div>
</template>

<script setup lang="ts">
import DashboardCards from '@/app/admin/dashboard/components/DashboardCards.vue'
import ManagementSearchButton from '@/app/admin/dashboard/ManagementSearchButton.vue'

const auth = authStore()
const route = useRouter()
</script>

<style scoped></style>
