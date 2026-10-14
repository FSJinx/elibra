<template>
  <div class="size-full flex-1 flex flex-col">
    <SectionHeader :pre="data?.campus.name" :title="data?.name ?? 'Library'" :description="`Manage ${data?.name}`" icon="building">
      <nav class="flex items-center justify-end gap-1 text-sm">
        <router-link v-for="nav in navRoutes" :key="nav.route" :to="{ name: nav.route }" class="py-1.5 px-3 rounded-lg border border-transparent transition-all duration-200" exact-active-class="bg-primary-soft/25 text-primary border-primary/50!">
          {{ nav.name }}
        </router-link>
      </nav>
    </SectionHeader>

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
