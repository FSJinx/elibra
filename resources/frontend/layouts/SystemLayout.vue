<template>
  <DesktopOnly v-if="isMobile" />
  <div class="flex w-screen h-screen overflow-hidden" v-else>
    <Sidebar />
    <main class="flex flex-col h-full w-full">
      <!-- HEADER  -->
      <div class="flex items-center p-3 px-4 h-max bg-white border-b border-gray-200 shrink-0 text-xs">
        <img :src="images.isu" class="h-9 w-auto mr-3" />

        <div class="flex flex-col">
          <h4 class="font-bold text-lg text-primary">Isabela State University - {{ campus }}</h4>
          <p class="text-neutral-500" v-if="my.role === 'librarian'">
            Primary Role: <span class="font-bold capitalize">{{ my.library?.role?.primary_role || 'No assigned role' }}</span>
          </p>
          <p class="text-neutral-500" v-else>Primary Role: <span class="font-bold capitalize">Administrator</span></p>
        </div>

        <div class="ml-auto flex flex-col text-end">
          <span class="font-bold text-lg">{{ time }}</span>
          <span class="text-neutral-600">{{ formatDate('complete') }}</span>
        </div>
      </div>

      <!-- BREADCRUMB  -->
      <div class="flex items-center gap-1 p-2 bg-secondary/10 text-xs shrink-0">
        <Home class="h-4 w-4 mr-1 text-gray-400" />
        <template v-for="(crumb, index) in breadcrumbs" :key="crumb.path">
          <router-link v-if="index !== breadcrumbs.length - 1" :to="crumb.path" class="text-neutral-500 hover:text-primary transition">
            {{ crumb.name }}
          </router-link>

          <span v-else class="font-semibold text-primary">
            {{ crumb.name }}
          </span>

          <ChevronRight v-if="index !== breadcrumbs.length - 1" class="w-4 h-4 text-neutral-400" />
        </template>
      </div>

      <div class="h-full w-full overflow-auto p-4">
        <div class="flex flex-col gap-1 mb-5 bg-inherit">
          <h1 class="text-3xl font-medium">{{ route.meta.title }}</h1>
          <p class="text-neutral-500 ml-0.5">{{ route.meta.description || 'No description for this page.' }}</p>
        </div>
        <RouterView />
      </div>
    </main>
  </div>
</template>

<script setup>
import images from '@/assets/images'
import DesktopOnly from '@/pages/errors/DesktopOnly.vue'
import Sidebar from '@/sections/Sidebar.vue'
import { isMobileDevice } from '@/utilities/mobileDetector'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import formatDate from '@/utilities/date'
import formatTime from '@/utilities/time'
import { authStore } from '@/stores/auth'

const time = ref(formatTime('full'))
const auth = authStore()
const my = auth.user

const campus = computed(() => {
  if (my.role === 'librarian') {
    return my.library.campus?.name
  } else if (my.role === 'admin') {
    return 'Global'
  }
})

let interval

onMounted(() => {
  interval = setInterval(() => {
    time.value = formatTime('full')
  }, 1000)
})

onUnmounted(() => {
  clearInterval(interval)
})

const isMobile = computed(() => isMobileDevice())

const route = useRoute()
const breadcrumbs = computed(() => {
  return route.matched.map((route) => ({
    name: route.meta.title || route.name,
    path: route.path,
  }))
})
</script>

<style scoped></style>
