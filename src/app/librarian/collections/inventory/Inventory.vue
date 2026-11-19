<template>
  <div class="flex flex-col size-full overflow-hidden">
    <!-- Header -->
    <SectionHeader title="Inventory" description="Create and print shelf-checking copies of your library collection" icon="boxes">
      <div class="flex items-center justify-end gap-2">
        <NewInventory @refresh="search" />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <Table title="Inventory History" subtitle="Recent inventory records for manual shelf-to-shelf verification" :data="pagination">
        <template #header>
          <Form class="w-full flex items-center justify-end gap-2" id="search-inventory" @submit="search">
            <Input class="max-w-125" id="search-query" type="text" placeholder="Search by inventory id..." v-model="params.query" />
            <Button type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <Th>No.</Th>
          <Th>Inventory Code</Th>
          <Th>Created By</Th>
          <Th>Date Created</Th>
          <Th>Status</Th>
          <Th>Action</Th>
        </Thead>

        <Tbody :loading="loading" :data="inventory.data">
          <Tr v-for="(item, index) in inventory.data" :key="item.id" :to="{ name: 'librarian.collections.inventory.view', params: { id: item.id } }">
            <Td :data="index + 1"></Td>
            <Td :data="item.inventory_code"></Td>
            <Td :data="item.librarian?.user?.name?.full"></Td>
            <Td :data="parse.formatDateAgo(item.created_at)"></Td>

            <Td>
              <Status :variant="item.status">{{ item.status }}</Status>
            </Td>
            <Td class="space-x-2">
              <Button class="hover:text-primary" size="sm" left-icon="printer" @click.stop="download(item)">Print PDF</Button>
              <Button class="hover:text-danger" size="sm" left-icon="trash" @click.stop="remove(item)">Delete</Button>
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
const auth = authStore()
const parse = useParser()

const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const params = reactive<Partial<InventoryParams>>({
  library_id: auth.user?.library?.id,
})

async function search() {
  loading.value = true
  try {
    const res = await inventory.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

async function download(item: Inventory) {
  const blob = await inventory.downloadPdf(item.id)
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `${item.inventory_code}.pdf`
  link.click()
  URL.revokeObjectURL(url)
}

async function remove(item: Inventory) {
  const pop = usePopup()
  const confirm = await pop.confirm({ text: `Delete inventory ${item.inventory_code}?` })
  if (!confirm.isConfirmed) return
  pop.load()
  try {
    await inventory.destory(item)
    pop.success('Inventory deleted successfully')
    await search()
  } catch (error: any) {
    pop.error(error?.response?.data?.message ?? 'Unable to delete inventory')
  } finally {
    pop.unload()
  }
}
onBeforeMount(search)
</script>
