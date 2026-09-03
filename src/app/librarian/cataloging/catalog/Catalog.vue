<template>
  <div class="flex flex-col size-full overflow-hidden">
    <SectionHeader title="Catalog" description="Browse all item found in your catalog" icon="journals" class="bg-background border-b border-border">
      <div class="flex items-end gap-2 ml-auto">
        <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.catalog.add-new' }"> Add New Item </Button>
      </div>
    </SectionHeader>

    <div class="flex-1 space-y-4 p-4 overflow-y-auto scroll">
      <!-- Statistical Cards -->
      <CatalogCards />

      <div class="flex flex-col bg-background border border-border divide-y divide-border rounded-2xl">
        <Form @submit="fetchCatalog" class="flex items-center justify-start gap-1 p-5">
          <Label id="catalog-query">Search</Label>
          <Input id="catalog-query" v-model="search" placeholder="Search for an item in the catalog..." class="max-w-150" enable-clear />
          <Button type="submit" icon="search" variant="success" class="mr-auto">Search</Button>
          <CatalogFilter @filterApplied="filter" />
          <Button variant="restore">Reset</Button>
        </Form>

        <!-- <template> -->
        <div class="flex items-center gap-3 flex-wrap p-5">
          <p class="font-medium">Filters:</p>
          <Chip enable-remove>Angadanan Campus</Chip>
          <Chip enable-remove>Angadanan Campus</Chip>
          <Chip enable-remove>Angadanan Campus</Chip>
          <Chip enable-remove>Angadanan Campus</Chip>
          <Chip enable-remove>Angadanan Campus</Chip>
          <Chip variant="danger">Reset</Chip>
        </div>
        <!-- </template> -->
      </div>

      <div class="flex h-200">
        <CatalogTable :data="items" :loading="loading" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import CatalogFilter from '@/app/librarian/cataloging/catalog/modals/CatalogFilter.vue'
import CatalogCards from '@/app/librarian/cataloging/catalog/sections/CatalogCards.vue'
import CatalogTable from '@/app/librarian/cataloging/catalog/sections/CatalogTable.vue'

interface CatalogItem {
  id: number
  title: string
  subtitle?: string | null
  call_number: string
  publication_year: number | null
}

const items = ref<CatalogItem[]>([])
const loading = ref(false)
const search = ref('')

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

async function fetchCatalog() {
  loading.value = true

  try {
    const res = await api.get('item/get', {
      params: { search: search.value, ...catalog },
    })

    items.value = res.data?.data ?? []
  } catch (error) {
    console.error('Failed to fetch catalog:', error)
    items.value = []
  } finally {
    loading.value = false
  }
}

function filter(f: any) {
  Object.assign(catalog, {
    category: f.category,
    status: f.status,
    sort: f.sort,
    order: f.order,
    item_type: f.item_type,
  })

  fetchCatalog()
}

onMounted(() => {
  fetchCatalog()
})
</script>
