<template>
  <div class="flex flex-col size-full overflow-hidden">
    <!-- Header -->
    <SectionHeader title="Stock Verification" description="Browse and verify materials on-shelf" icon="boxes">
      <div class="flex items-center justify-end gap-2">
        <NewInventory @refresh="search" />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <Table title="Recent Inventory Tracking" subtitle="Track your inventory history here." :data="pagination">
        <template #header>
          <Form class="w-full flex items-center justify-end gap-2" id="search-inventory" @submit="search">
            <Input class="max-w-125" id="search-query" type="text" placeholder="Search by inventory id..." v-model="params.query" />
            <Button type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <Th>No.</Th>
          <Th>Inventory Code</Th>
          <Th>Shelf Location</Th>
          <Th>Created By</Th>
          <Th>Date Created</Th>
          <Th>Status</Th>
          <Th>Action</Th>
        </Thead>

        <Tbody :loading="loading" :data="inventory.data">
          <Tr v-for="(item, index) in inventory.data" :key="item.id" :to="{ name: 'librarian.collections.inventory.view', params: { id: item.id } }">
            <Td :data="index + 1"></Td>
            <Td :data="item.inventory_code"></Td>
            <Td :data="item.section ? item.section?.name : 'Unshelved'"></Td>
            <Td :data="item.librarian?.user?.name?.full"></Td>
            <Td :data="parse.formatDateAgo(item.created_at)"></Td>

            <Td>
              <Status :variant="item.status">{{ item.status }}</Status>
            </Td>
            <Td class="space-x-2">
              <Button class="hover:text-primary" size="sm">Finish</Button>
              <Button class="hover:text-danger" size="sm">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewInventory from '@/app/librarian/collections/inventory/modals/NewInventory.vue'

const inventory = inventoryStore()
const parse = useParser()

const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const params = reactive<Partial<InventoryParams>>({})

async function search() {
  loading.value = true
  try {
    const res = await inventory.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}
onBeforeMount(search)
</script>
