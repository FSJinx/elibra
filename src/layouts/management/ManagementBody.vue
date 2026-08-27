<template>
  <main class="relative flex-1 flex flex-col overflow-hidden bg-slate-50 transition-all duration-200">
    <!-- Main Body Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Page Heading -->
      <header class="flex flex-col gap-4 p-2 sm:flex-row sm:items-center sm:justify-between" v-if="$route.meta.title">
        <section class="flex items-end justify-between w-full rounded-xl bg-slate-950 p-5 text-white">
          <div>
            <p class="text-xs font-bold uppercase tracking-wide text-emerald-300 mb-2">{{ auth.user?.role }} Desk</p>
            <H1 class="capitalize">
              <template v-if="$route.name && $route.name.toString().includes('overview')"> Good day, {{ auth.user?.first_name }}! </template>
              <template v-else>{{ $route.meta?.title }}</template>
            </H1>
            <p class="mt-1 max-w-2xl text-sm text-slate-300">{{ $route.meta.description ?? 'This is the default description for pages.' }}</p>
          </div>

          <div class="flex items-center gap-2 text-slate-300">
            <Icon icon="calendar" />
            <span>{{ today }}</span>
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
const parse = useParser()
const today = ref()

const currentDate = () => {
  const now = new Date()
  const date = now.toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  })

  const time = now.toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    second: 'numeric',
  })

  today.value = `Today, ${date} - ${time}`
}

setInterval(() => {
  currentDate()
}, 1000)
</script>

<style scoped></style>
