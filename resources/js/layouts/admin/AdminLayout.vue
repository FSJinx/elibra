<template>
  <div class="h-screen w-full flex overflow-hidden">
    <AdminSidebar />

    <main class="flex flex-col flex-1 overflow-hidden">
      <!-- HEADER  -->
      <div class="flex items-center p-4 px-5 h-20 bg-white border-b border-gray-200 shrink-0">
        <img :src="images.isu" class="h-11 w-auto mr-3" />

        <div class="flex flex-col">
          <span class="font-bold text-xl text-primary"> Isabela State University - Global </span>
          <p class="text-neutral-500">Library Management System</p>
        </div>

        <div class="ml-auto flex flex-col text-end">
          <span class="font-bold text-xl">{{ time }}</span>
          <span>{{ formatDate('complete') }}</span>
        </div>
      </div>

      <!-- BREADCRUMB  -->
      <div class="flex items-center gap-1 p-2 bg-secondary/10 text-xs shrink-0">
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

      <!-- CONTENT  -->
      <div class="flex-1 overflow-y-auto p-5">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'

import AdminSidebar from '@/components/admin/AdminSidebar.vue'
import images from '@/assets/images'

import formatDate from '@/utils/date'
import formatTime from '@/utils/time'
import { useRoute } from 'vue-router'

const time = ref(formatTime('full'))

let interval

onMounted(() => {
  interval = setInterval(() => {
    time.value = formatTime('full')
  }, 1000)
})

onUnmounted(() => {
  clearInterval(interval)
})

const route = useRoute()

const breadcrumbs = computed(() => {
  return route.matched.map((route) => ({
    name: route.meta.breadcrumb || route.name,
    path: route.path,
  }))
})
</script>
