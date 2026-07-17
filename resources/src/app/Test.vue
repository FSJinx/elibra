<template>
  <div class="flex h-screen w-full bg-slate-50 text-slate-800 antialiased overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-80 border-r border-slate-200 bg-white flex flex-col justify-between">
      <div>
        <!-- Header / Logo Area -->
        <div class="p-6 border-b border-slate-100 flex items-center gap-3">
          <div class="h-9 w-9 rounded-lg bg-red-600 flex items-center justify-center text-white shadow-md shadow-red-100">
            <Library class="h-5 w-5" />
          </div>
          <div>
            <h1 class="text-lg font-bold tracking-tight text-slate-900">e-Libra UI</h1>
            <p class="text-xs text-slate-400 font-medium">Component Library v1.0</p>
          </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-2 space-y-1.5">
          <div v-for="page in pages" :key="page.name" class="space-y-1">
            <!-- Parent Category Button -->
            <button class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200 group" @click="togglePage(page.name)">
              <div class="flex items-center gap-3">
                <component :is="page.icon" class="h-4.5 w-4.5 text-slate-400 group-hover:text-red-600 transition-colors" />
                <span>{{ page.name }}</span>
              </div>

              <ChevronDown class="h-4 w-4 text-slate-400 transition-transform duration-300" :class="page.expanded && 'rotate-180 text-red-600'" />
            </button>

            <!-- Children Sub-menu (Smooth Slide Transition) -->
            <Transition enter-active-class="transition-[max-height,opacity] duration-300 ease-out overflow-hidden" leave-active-class="transition-[max-height,opacity] duration-250 ease-in overflow-hidden" enter-from-class="max-h-0 opacity-0" enter-to-class="max-h-40 opacity-100" leave-from-class="max-h-40 opacity-100" leave-to-class="max-h-0 opacity-0">
              <div v-show="page.expanded" class="pl-9 space-y-1 overflow-hidden">
                <button v-for="child in page.children" :key="child.name" class="relative flex items-center w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200" :class="[current.page === page.name && current.child === child.name ? 'bg-red-50/70 text-red-600 font-semibold shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50']" @click="selectChild(page.name, child.name)">
                  <!-- Active Dot Indicator -->
                  <span v-if="current.page === page.name && current.child === child.name" class="absolute left-0 w-1 h-5 bg-red-600 rounded-r-full" />
                  {{ child.name }}
                </button>
              </div>
            </Transition>
          </div>
        </nav>
      </div>

      <!-- User Profile / Footer Section in Sidebar -->
      <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center gap-3 px-2 py-1.5">
          <div class="h-9 w-9 rounded-full bg-slate-200 border-2 border-white shadow-sm flex items-center justify-center font-bold text-slate-600 text-sm">EL</div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-700 truncate">Developer Mode</p>
            <p class="text-xs text-slate-400 truncate">sandbox@elibra.io</p>
          </div>
        </div>
      </div>
    </aside>

    <!-- Preview Area -->
    <main class="flex-1 overflow-auto bg-slate-50 flex flex-col">
      <!-- Topbar Header -->
      <header class="h-16 border-b border-slate-200 bg-white px-8 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2 text-sm text-slate-500">
          <span>Pages</span>
          <span class="text-slate-300">/</span>
          <span class="text-slate-700 font-semibold">{{ current.page }}</span>
          <span class="text-slate-300">/</span>
          <span class="text-red-600 font-bold bg-red-50 px-2.5 py-1 rounded-md text-xs">{{ current.child }}</span>
        </div>
      </header>

      <!-- Component Render Canvas -->
      <div class="flex-1 p-8 max-w-6xl w-full mx-auto">
        <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm min-h-100 flex flex-col justify-center items-center">
          <Transition name="fade-component" mode="out-in">
            <component v-if="currentComponent" :is="currentComponent" :key="`${current.page}-${current.child}`" />
            <div v-else class="text-center text-slate-400">
              <Sparkles class="h-8 w-8 mx-auto mb-2 opacity-50" />
              <p>No component selected</p>
            </div>
          </Transition>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

import TestButton from './test/TestButton.vue'
import TestInput from './test/TestInput.vue'

// Ginamitan ng ref() para fully reactive ang expanded states
const pages = ref([
  {
    name: 'Components',
    expanded: true,
    icon: 'ComponentIcon',
    children: [
      {
        name: 'Button',
        component: TestButton,
      },
      {
        name: 'Input',
        component: TestInput,
      },
    ],
  },
  {
    name: 'Utilities',
    expanded: false,
    icon: 'Wrench',
    children: [],
  },
])

const current = ref({
  page: 'Components',
  child: 'Button',
})

// Safe at reactive state toggling
const togglePage = (pageName: string) => {
  const target = pages.value.find((p) => p.name === pageName)
  if (target) {
    target.expanded = !target.expanded
  }
}

const selectChild = (page: string, child: string) => {
  current.value.page = page
  current.value.child = child
}

const currentComponent = computed(() => {
  const page = pages.value.find((p) => p.name === current.value.page)
  if (!page) return null

  const child = page.children.find((c) => c.name === current.value.child)
  return child?.component ?? null
})
</script>

<style scoped>
/* Swabe at modernong fade para sa transition ng components */
.fade-component-enter-active,
.fade-component-leave-active {
  transition:
    opacity 0.2s ease,
    transform 0.2s ease;
}

.fade-component-enter-from {
  opacity: 0;
  transform: translateY(4px);
}

.fade-component-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>
