<template>
  <main class="relative flex-1 flex flex-col overflow-hidden bg-slate-50 transition-all duration-200">
    <!-- Main Body Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Page Heading -->
      <header class="flex flex-col gap-4 p-5 sm:flex-row sm:items-end sm:justify-between" v-if="($route.name as string).includes('.dashboard')">
        <div>
          <p class="mb-2 text-sm font-medium uppercase tracking-wide text-primary">{{ auth.user?.role }} workspace</p>
          <h1 class="text-3xl font-semibold tracking-tight text-slate-900">
            Good morning, <span class="capitalize">{{ auth.user?.first_name }}</span>
          </h1>
          <p class="mt-1 max-w-2xl text-sm text-slate-500">Your collection overview and daily library activity will appear here.</p>
        </div>

        <div class="flex items-center gap-2 text-slate-500">
          <Icon icon="calendar" />
          <span>{{ today }}</span>
        </div>
      </header>
      <div class="flex-1 flex flex-col overflow-y-auto scrollbar-none transition-all duration-200">
        <router-view />
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
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
