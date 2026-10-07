<template>
  <div class="flex flex-col size-full overflow-hidden">
    <SectionHeader title="Catalog" description="Browse all item found in your catalog" icon="journals" />

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <Card>
        <Form @submit="search()" class="grid grid-cols-2 gap-2">
          <div class="flex items-center gap-2">
            <Input id="catalog-query" v-model="items.params.query" placeholder="Search for an item in the catalog..." class="max-w-150" enable-clear />
            <Button type="submit" icon="search" variant="success">Search</Button>
            <CatalogFilter @filterApplied="filter" />
          </div>

          <div class="flex items-center justify-end gap-2">
            <Button variant="restore" @click="reset()">Reset</Button>
            <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.collections.catalog.new' }"> New Item </Button>
          </div>
        </Form>
      </Card>

      <CatalogTable :data="items.data" :loading="loading" />
    </div>
  </div>
</template>

<script setup lang="ts">
import CatalogFilter from '@/app/librarian/collections/catalog/modals/CatalogFilter.vue'
import CatalogTable from '@/app/librarian/collections/catalog/sections/CatalogTable.vue'

const items = useItemStore()
const loading = ref(false)
const stats_expanded = ref(false)

const params = reactive<Partial<ItemParams>>(itemDefaultParams())

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

async function search() {
  try {
    loading.value = true
    await items.fetch(true)
  } catch (e: any) {
    throw e
  } finally {
    loading.value = false
  }
}

function reset() {
  Object.assign(items.params, itemDefaultParams())
  nextTick(() => search())
}

watchDebounced(
  () => params.query,
  () => {
    search()
  },
  {
    debounce: 300,
  },
)
</script>
