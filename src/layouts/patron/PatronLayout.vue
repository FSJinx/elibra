<template>
  <div class="min-h-dvh bg-[#f4f7f4] text-slate-900">
    <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-[#f4f7f4]/95 backdrop-blur">
      <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
        <router-link :to="{ name: 'patron.dashboard' }" class="flex items-center gap-3">
          <span class="grid size-10 place-items-center rounded-xl bg-emerald-700 text-white shadow-sm"><Icon icon="book" /></span>
          <span><strong class="block text-lg tracking-tight">e-Libra</strong><span class="hidden text-xs text-slate-500 sm:block">Your library, in your hands</span></span>
        </router-link>
        <nav class="hidden items-center gap-1 md:flex" aria-label="Patron navigation">
          <router-link v-for="item in navigation" :key="item.name" :to="{ name: item.name }" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-500 transition hover:bg-white hover:text-emerald-700" active-class="bg-white text-emerald-700 shadow-sm">{{ item.label }}</router-link>
        </nav>
        <router-link :to="{ name: 'patron.profile' }" class="grid size-10 place-items-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-800" aria-label="Open profile">{{ auth.getInitials || 'RD' }}</router-link>
      </div>
    </header>
    <router-view />
    <nav class="fixed inset-x-0 bottom-0 z-20 flex justify-around border-t border-slate-200 bg-white/95 px-2 py-2 shadow-[0_-8px_30px_rgba(15,23,42,0.08)] backdrop-blur md:hidden" aria-label="Mobile navigation">
      <router-link v-for="item in navigation" :key="item.name" :to="{ name: item.name }" class="flex min-w-18 flex-col items-center gap-1 rounded-lg px-2 py-1 text-[11px] font-semibold text-slate-400" active-class="bg-emerald-50 text-emerald-700"><Icon :icon="item.icon" class="text-base" />{{ item.label }}</router-link>
    </nav>
  </div>
</template>

<script setup lang="ts">
const auth = authStore()
const navigation = [
  { name: 'patron.dashboard', label: 'Home', icon: 'house' },
  { name: 'OPAC', label: 'Discover', icon: 'search' },
  { name: 'patron.services', label: 'Services', icon: 'grid' },
  { name: 'patron.profile', label: 'Profile', icon: 'person' },
]
</script>

<style scoped></style>
