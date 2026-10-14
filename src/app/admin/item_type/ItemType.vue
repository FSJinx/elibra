<template>
  <div class="flex flex-col size-full">
    <SectionHeader title="Item Types" description="Manage item types for item classification and advanced searching" icon="tags" />

    <div class="flex-1 flex flex-col gap-3 p-5">
      <div class="grid grid-cols-2 gap-2 p-5 bg-background border border-border rounded-xl">
        <div class="flex items-center gap-2">
          <Input id="search-item-type" class="max-w-100" placeholder="Search item type..." />
          <Button @click="item_type.fetch(true)">Refresh</Button>
        </div>

        <div class="flex items-center justify-end gap-2">
          <NewItemTypeModal />
        </div>
      </div>

      <Table title="Item Types Table" subtitle="List of all item types" :data-length="item_type.data?.length">
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th>Code</th>
            <th>Loanable</th>
            <th>Last Modified</th>
            <th>Actions</th>
          </tr>
        </Thead>
        <Tbody :data="item_type.data" :loading="item_type.loading" cols="6">
          <tr v-for="(type, index) in item_type.data" :key="type.id">
            <Td :data="index + 1" />
            <Td class="text-left" :data="type.name" />
            <Td :data="type.slug" />
            <Td :data="type.loanable ? 'Yes' : 'No'" />
            <Td :data="parse.dateTimeAgo(type.updated_at)" />
            <Td class="space-x-2">
              <EditItemTypeModal :data="type" />
              <Button size="sm" class="hover:text-danger" @click="remove(type)">Delete</Button>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewItemTypeModal from '@/app/admin/item_type/modals/NewItemTypeModal.vue'
import EditItemTypeModal from '@/app/admin/item_type/modals/UpdateItemTypeModal.vue'

const item_type = useAdminItemTypeStore()
const parse = useParser()
const pop = usePopup()

async function remove(type: ItemType) {
  const confirm = await pop.confirm({ text: `Are you sure you want to delete ${type.name}?` })
  if (!confirm.isConfirmed) return

  pop.load()
  try {
    const res = await item_type.remove(type)
    pop.success(res.message ?? 'Item type deleted successfully')
  } catch (e: any) {
    pop.error(e.response?.data?.message ?? 'Failed to delete item type')
    throw e
  }
}

onBeforeMount(async () => {
  await item_type.fetch()
})
</script>

<style scoped></style>
