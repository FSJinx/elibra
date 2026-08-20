<template>
  <main class="relative flex-1 flex flex-col overflow-hidden transition-all duration-200">
    <!-- Main Body Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <div class="flex-1 flex flex-col overflow-y-auto scrollbar-none transition-all duration-200">
        <!-- Page Heading -->
        <div class="shrink-0 p-5" v-if="$route.meta.title">
          <p class="text-primary text-lg uppercase tracking-wide font-medium text-shadow-md text-shadow-primary/15 mb-1" v-if="$route.name?.toString().includes('.dashboard')">Good day, {{ auth.user?.first_name }}!👋</p>
          <h1 class="text-2xl font-semibold" v-if="$route.meta.title">{{ $route.meta.title }}</h1>
          <p class="text-foreground-secondary">{{ $route.meta.description ?? `This is your today's preview for ${$route.meta.title}.` }}</p>
        </div>
        <router-view />
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
</script>

<style scoped></style>
