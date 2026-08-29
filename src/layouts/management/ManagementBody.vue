<template>
  <main class="relative flex-1 flex flex-col overflow-hidden bg-slate-50 transition-all duration-200">
    <!-- Main Body Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Page Heading -->
      <header class="flex flex-col gap-4 p-2 pb-0 sm:flex-row sm:items-center sm:justify-between" v-if="$route.meta.title">
        <section class="flex items-end justify-between w-full rounded-xl bg-linear-to-r from-slate-950 to-slate-800 p-5 text-white">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-300 mb-2">{{ auth.user?.role }} Desk</p>
            <h1 class="text-3xl capitalize font-bold">
              <template v-if="$route.name && $route.name.toString().includes('overview')"> Good day, {{ auth.user?.first_name }}! </template>
              <template v-else>{{ $route.meta?.title }}</template>
            </h1>
            <p class="mt-1 max-w-2xl text-sm text-slate-300">{{ $route.meta.description ?? 'This is the default description for pages.' }}</p>
          </div>

          <div class="flex items-center gap-2 text-slate-300">
            <Icon icon="calendar" />
            <span>{{ clock.today }}</span>
          </div>
        </section>
      </header>
      <div class="flex-1 overflow-y-auto scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/20 transition-all duration-200">
        <router-view />
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
const clock = useClock()
</script>

<style scoped></style>
