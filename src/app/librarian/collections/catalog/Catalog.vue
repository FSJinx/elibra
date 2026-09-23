<template>
  <div class="flex flex-col size-full overflow-hidden">
    <SectionHeader title="Catalog" description="Browse all item found in your catalog" icon="journals">
      <div class="flex items-end gap-2 ml-auto">
        <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.collections.catalog.add-new' }"> Add New Item </Button>
        <Button :icon="stats_expanded ? 'arrows-angle-contract' : 'arrows-angle-expand'" :data-title="stats_expanded ? 'Hide stat cards' : 'Show stat cards'" @click="stats_expanded = !stats_expanded"></Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <CatalogCards v-if="stats_expanded" />
      <Form @submit="items.fetch" class="flex items-end justify-end gap-2" v-else>
        <!-- <Label id="catalog-query">Search</Label> -->
        <Input id="catalog-query" v-model="search" placeholder="Search for an item in the catalog..." class="max-w-150" enable-clear />
        <!-- <Button type="submit" icon="search" variant="success">Search</Button> -->
        <CatalogFilter @filterApplied="filter" />
        <Button variant="restore" @click="items.fetch">Reset</Button>
      </Form>
      <CatalogTable :data="items.data" :loading="loading" />
    </div>
  </div>
</template>

<script setup lang="ts">
import CatalogFilter from '@/app/librarian/collections/catalog/modals/CatalogFilter.vue'
import CatalogCards from '@/app/librarian/collections/catalog/sections/CatalogCards.vue'
import CatalogTable from '@/app/librarian/collections/catalog/sections/CatalogTable.vue'

interface CatalogItem {
  id: number
  title: string
  subtitle?: string | null
  call_number: string
  publication_year: number | null
}

const items = useItemStore()
const loading = ref(false)
const search = ref('')
const stats_expanded = ref(false)

const catalog = reactive<{
  category: string
  status: string
  sort: string | null
  order: 'asc' | 'desc'
  item_type: number | string | null
}>({
  category: '',
  status: '',
  sort: '',
  order: 'asc',
  item_type: '',
})

function filter(f: any) {
  Object.assign(catalog, {
    category: f.category,
    status: f.status,
    sort: f.sort,
    order: f.order,
    item_type: f.item_type,
  })
}
</script>
