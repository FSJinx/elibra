<template>
  <main class="relative flex-1 flex flex-col overflow-hidden bg-background border border-border rounded-2xl font-poppins">
    <!-- Main Body Header -->
    <div class="sticky top-0 flex flex-col gap-3 p-5 bg-background">
      <div class="flex md:items-center flex-col md:flex-row gap-5">
        <div class="flex items-center gap-3">
          <Button variant="default" @click="system.toggleSidebar" class="group hover:drop-shadow">
            <Icon :icon="system.sidebar ? 'box-arrow-in-left' : 'box-arrow-in-right'" class="transition-all duration-300" />
          </Button>
          
        </div>
        
        <div class="flex items-end gap-1.5 ml-auto">
          <ManagementSearchButton />
          <Button variant="default"><Icon icon="bell" /></Button>
          <Button variant="default" icon="bell"></Button>
          <Button variant="default">Text</Button>
        </div>
      </div>
      
      <div class="space-y-1">
        <p class="text-primary text-md uppercase tracking-wide font-medium text-shadow-md text-shadow-primary/15">Good day, {{ auth.user?.first_name }}!👋</p>
        <h1 class="text-2xl font-semibold">{{ route.meta.title ?? 'Untitled' }}</h1>
        <p class="text-sm text-foreground-secondary">{{ route.meta.description ?? `This is you're today's preview for ${route.meta.title}.` }}</p>
      </div>
    </div>

    <!-- Main Body Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <div class="flex-1 flex flex-col rounded-xl p-5 overflow-y-auto scrollbar-none transition-all duration-200">
        <router-view />
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import ManagementSearchButton from '@/layouts/management/ManagementSearchButton.vue'

const route = useRoute()
const auth = authStore()
const system = systemStore()
</script>

<style scoped></style>
