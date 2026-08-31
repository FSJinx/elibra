<template>
  <main class="relative flex min-h-0 flex-1 flex-col overflow-hidden bg-secondary">
    <div class="flex min-h-0 flex-1 flex-col overflow-hidden">
      <!-- Page Header -->
      <!-- <header v-if="$route.meta.title" class="shrink-0 p-5 ">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
          Page Information
          <div class="min-w-0">
            <p class="mb-1.5 text-xs font-semibold uppercase tracking-widest text-primary">{{ auth.user?.role }} Desk</p>

            <h1 class="text-2xl font-bold capitalize tracking-tight text-foreground">
              <template v-if="$route.name && $route.name.toString().includes('overview')"> Good day, {{ auth.user?.first_name }}! </template>

              <template v-else>
                {{ $route.meta.title }}
              </template>
            </h1>

            <p class="mt-1 max-w-2xl text-sm leading-relaxed text-foreground-secondary">
              {{ $route.meta.description ?? 'This is the default description for pages.' }}
            </p>
          </div>

          Date
          <div class="flex shrink-0 items-center gap-2 text-sm text-foreground-secondary">
            <Icon icon="calendar" />
            <time>
              {{ clock.today }}
            </time>
          </div>
        </div>
      </header> -->

      <!-- Page Content -->
      <div class="min-h-0 flex-1 overflow-y-auto scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/20 transition-all duration-200">
        <router-view />
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
const clock = useClock()
</script>
