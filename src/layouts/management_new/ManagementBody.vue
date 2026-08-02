<template>
  <main class="flex-1 overflow-auto py-5 pr-5 font-poppins scrollbar-thumb-transparent hover:scrollbar-thumb-background-inverse/25 transition-all duration-200">
    <div class="flex flex-col-reverse lg:flex-row py-5">
      <div>
        <p class="text-primary uppercase tracking-wide text-sm font-semibold mb-2">Good day, {{ auth.user?.first_name }}!👋</p>
        <h1 class="text-2xl font-semibold">{{ route.meta.title ?? 'Untitled' }}</h1>
        <p class="text-md text-muted">{{ route.meta.description ?? `This is you're today's preview for ${route.meta.title}.` }}</p>
      </div>

      <div class="flex items-end gap-2 justify-end ml-auto">
        <ManagementSearch />
        <Button class="bg-background border border-border">
          <Icon icon="bell" />
        </Button>
      </div>
    </div>

    <router-view />
  </main>
</template>

<script setup lang="ts">
import ManagementSearch from '@/layouts/management_new/ManagementSearch.vue'

const route = useRoute()
const auth = authStore()
</script>

<style scoped></style>
