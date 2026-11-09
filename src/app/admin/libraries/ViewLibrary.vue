<template>
  <div class="size-full flex-1 flex flex-col">
    <div class="flex items-center gap-2 bg-background border-b border-border p-3">
      <Button icon="arrow-left" @click="router.push({ name: 'admin.libraries' })" variant="text" data-title="Return" />
      <Title :level="3">{{ library.currentData?.name }} - {{ library.currentData?.campus?.name }}</Title>

      <nav class="flex items-center justify-end gap-1 text-sm ml-auto mr-3">
        <router-link v-for="nav in navRoutes" :key="nav.route" :to="{ name: nav.route }" class="py-1.5 px-3 rounded-lg border border-transparent transition-all duration-200" exact-active-class="bg-primary-soft/25 text-primary border-primary/50!">
          {{ nav.name }}
        </router-link>
      </nav>
    </div>

    <router-view />
  </div>
</template>

<script setup lang="ts">
const library = libraryStore()
const data = library.currentData

const navRoutes = [
  { name: 'Overview', route: 'admin.libraries.view.overview' },
  { name: 'Sections', route: 'admin.libraries.view.sections' },
]
</script>

<style scoped></style>
