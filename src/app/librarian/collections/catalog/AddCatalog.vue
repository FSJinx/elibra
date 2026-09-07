<template>
  <div class="sticky top-0 z-20 border-b border-border/70 bg-background shadow-sm">
    <!-- Back Navigation -->
    <div class="px-5 py-3">
      <Button :to="{ name: 'librarian.collections.catalog' }" as="link" variant="text" class="gap-3" icon="arrow-left">
        <span>Back to Catalog</span>
      </Button>
    </div>

    <!-- Detail Navigation -->
    <nav aria-label="Catalog item navigation" class="flex items-center px-5">
      <router-link v-for="item in links" :key="item.path" :to="{ name: item.path }" class="group relative flex justify-center px-3 py-3 transition-colors" :class="[active(item.path) ? 'text-primary' : 'font-medium text-muted-foreground hover:text-primary']">
        {{ item.name }}

        <!-- Active Indicator -->
        <span class="absolute bottom-0 h-0.75 rounded-full bg-primary transition-all duration-200" :class="active(item.path) ? 'w-full' : 'w-0 group-hover:w-full'" />
      </router-link>
    </nav>
  </div>

  <div class="w-full max-w-7xl mx-auto">
    <router-view />
  </div>
</template>
<script setup lang="ts">
const route = useRoute()

const links = [
  { path: 'librarian.collections.catalog.add-new.book', name: 'Book' },
  { path: 'librarian.collections.catalog.add-new.academics', name: 'Academics' },
  { path: 'librarian.collections.catalog.add-new.serials', name: 'Serials' },
]

const active = (path: string) => {
  return route.name === path
}
</script>

<style scoped></style>
