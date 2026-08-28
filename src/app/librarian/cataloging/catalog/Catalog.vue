<template>
  <div class="flex flex-col size-full">
    <!-- Header & Action -->
    <div class="grid grid-cols-2 items-center px-4 py-5">
      <div class="">
        <H6>Catalog List</H6>
        <p class="text-sm text-foreground-secondary">{{ items?.length ?? 0 }} records</p>
      </div>

      <div class="flex items-center justify-end gap-2">
        <Input id="catalog-query" placeholder="Search for an item in the catalog..." class="max-w-100" enable-clear />
        <CatalogFilter />
        <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.cataloging.add-new' }">Add New Item</Button>
      </div>
    </div>

    <!-- Catalog Table -->
    <Table>
      <Thead>
        <tr>
          <Th>No</Th>
          <Th class="text-left">Title</Th>
          <Th>Call Number</Th>
          <Th>Publication Year</Th>
        </tr>
      </Thead>
      <Tbody :columns="4" :loading="loading" :data="items">
        <tr v-for="(item, index) in items">
          <Td :data="(index as number) + 1" />
          <Td class="text-left">
            <p class="text-lg font-medium">{{ item.title }}</p>
            <p>{{ item.subtitle }}</p>
          </Td>
          <Td :data="item.call_number" />
          <Td :data="item.publication_year" />
        </tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
import CatalogFilter from '@/app/librarian/cataloging/catalog/CatalogFilter.vue'

const items = ref()
const loading = ref<boolean>(false)

async function fetchCatalog() {
  loading.value = true

  await api
    .get('item/get')
    .then((res) => {
      items.value = res.data?.data
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  fetchCatalog()
})
</script>

<style scoped></style>
