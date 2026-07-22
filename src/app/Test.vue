<template>
  <div class="flex h-screen w-full bg-slate-50 text-slate-800 antialiased overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-80 border-r border-slate-200 bg-white flex flex-col justify-between">
      <div>
        <!-- Header / Logo Area -->
        <div class="p-6 border-b border-slate-100 flex items-center gap-3">
          <div class="h-9 w-9 rounded-lg bg-primary flex items-center justify-center text-primary-foreground shadow-md shadow-indigo-100">
            <Logo width="18" />
          </div>
          <div>
            <h1 class="text-lg font-bold tracking-tight text-slate-900">e-Libra UI</h1>
            <p class="text-xs text-slate-400 font-medium">UI Library v1.0</p>
          </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-2 space-y-1.5">
          <div v-for="page in pages" :key="page.name" class="space-y-1">
            <!-- Parent Category Button -->
            <button class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200 group" @click="togglePage(page.name)">
              <div class="flex items-center gap-3">
                <component :is="page.icon" class="h-4.5 w-4.5 text-slate-400 group-hover:text-primary transition-colors" />
                <span>{{ page.name }}</span>
              </div>

              <ChevronDown class="h-4 w-4 text-slate-400 transition-transform duration-300" :class="currentPage === page.name && 'rotate-180 text-primary'" />
            </button>

            <!-- Children Sub-menu (Smooth Slide Transition) -->
            <Transition enter-active-class="transition-[max-height,opacity] duration-300 ease-out overflow-hidden" leave-active-class="transition-[max-height,opacity] duration-250 ease-in overflow-hidden" enter-from-class="max-h-0 opacity-0" enter-to-class="max-h-40 opacity-100" leave-from-class="max-h-40 opacity-100" leave-to-class="max-h-0 opacity-0">
              <div v-show="currentPage === page.name" class="pl-9 space-y-1 overflow-hidden">
                <button v-for="child in page.children" :key="child.name" class="relative flex items-center w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200" :class="[currentPage === page.name && currentComponent === child.component ? 'bg-primary-soft text-primary font-semibold shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50']" @click="open(page.name, child.name)">
                  <!-- Active Dot Indicator -->
                  <span v-if="currentPage === page.name && currentComponent === child.component" class="absolute left-0 w-1 h-5 bg-primary rounded-r-full" />
                  {{ child.name }}
                </button>
              </div>
            </Transition>
          </div>
        </nav>
      </div>

      <!-- User Profile / Footer Section in Sidebar -->
      <div class="p-4 border-t border-secondary bg-background">
        <div class="flex items-center gap-3 px-2 py-1.5">
          <div class="h-9 w-9 rounded-full bg-primary-light/50 border-2 border-white shadow-sm flex items-center justify-center font-bold text-primary text-sm">EL</div>
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
      <header class="h-16 border-b border-slate-200 bg-white px-8 flex items-center justify-between shadow-sm shrink-0">
        <div class="flex items-center gap-2 text-sm text-slate-500">
          <span>Pages</span>
          <span class="text-slate-300">|</span>
          <span class="text-slate-700 font-semibold">{{ currentPage }}</span>
          <span class="text-slate-300">|</span>
          <span class="text-primary font-bold bg-indigo-50 px-2.5 py-1 rounded-md text-xs">{{ page.name }}</span>
        </div>
      </header>

      <!-- Component Render Canvas -->
      <div class="flex-1 p-8 max-w-4xl w-full mx-auto">
        <Transition name="fade-component" mode="out-in">
          <div class="bg-white border border-slate-200 rounded-2xl p-10 shadow-sm">
            <h1 class="text-2xl font-bold mb-5">{{ page.name }}</h1>
            <div class="flex flex-col item-center divide-y divide-slate-200 space-y-5">
              <component v-if="currentComponent" :is="currentComponent" :key="`${currentComponent}`" />
            </div>
          </div>
        </Transition>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import TestButton from '@/app/test/TestButton.vue'
import TestColor from '@/app/test/TestColor.vue'
import TestInput from '@/app/test/TestInput.vue'

const pages = ref([
  {
    name: 'Components',
    icon: 'ComponentIcon',
    children: [
      {
        name: 'Buttons',
        component: markRaw(TestButton),
      },
      {
        name: 'Inputs',
        component: markRaw(TestInput),
      },
    ],
  },
  {
    name: 'Utilities',
    icon: 'Wrench',
    children: [
      {
        name: 'Colors',
        component: markRaw(TestColor),
      },
    ],
  },
])

const route = useRoute()
const currentPage = computed(() => (route.query.page as string) ?? pages.value[0].name)
const currentComponent = computed(() => {
  const x = pages.value.find((p) => p.name === currentPage.value)

  const child = x?.children.find((c) => c.name === route.query.component)

  return child?.component ?? pages.value[0].children[0].component
})

const page = {
  name: route.params.component ?? pages.value[0].children[0].name,
  component: route.params.component ? currentComponent.value : pages.value[0].children[0].component,
}

const open = (page: string, component: string) => {
  router.push(`/test?page=${page}&component=${component}`)
}

const togglePage = (p: string) => {
  router.push(`/test?page=${p}${page.name ? '&component=' + page.name : ''}`)
}
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
