<template>
  <div class="relative flex flex-col gap-5 w-full max-w-7xl mx-auto p-5">
    <!-- Item Header -->
    <Card class="flex flex-col">
      <div class="flex items-start justify-between mb-5">
        <Button :to="{ name: 'librarian.collections.catalog' }" as="link" variant="text" class="gap-3" icon="arrow-left">
          <span class="text-lg">Back to catalog</span>
        </Button>

        <div class="flex gap-2">
          <Button left-icon="trash" variant="danger">Delete</Button>
        </div>
      </div>

      <!-- Item Navigation -->
      <nav class="flex text-sm gap-1">
        <Button as="link" variant="text" size="sm" :to="{ name: nav.path }" v-for="nav in navs" class="hover:bg-primary-soft hover:text-primary" exact-active-class="bg-primary-soft text-primary">
          {{ nav.name }}
        </Button>
      </nav>
    </Card>

    <!-- Item Preview -->
    <div class="w-full">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const items = useItemStore()
const item_type = useItemTypeStore()

const navs = [
  { name: 'Overview', path: 'librarian.collections.catalog.view.overview' },
  { name: 'Authors', path: 'librarian.collections.catalog.view.authors' },
  { name: 'Accession', path: 'librarian.collections.catalog.view.accession' },
  { name: 'Acquisition History', path: 'librarian.collections.catalog.view.acquisition' },
]

const active = (path: string) => {
  return route.name === path
}
</script>
