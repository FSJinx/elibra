<template>
  <div class="flex flex-col size-full">
    <SectionHeader title="Item Categories" description="Manage item categories of each item types for item classification and advanced searching" icon="collection" />

    <div class="flex-1 flex flex-col gap-3 p-5">
      <div class="grid grid-cols-2 gap-2 p-5 bg-background border border-border rounded-xl">
        <div class="flex items-center gap-2">
          <Input id="search-item-category" class="max-w-100" placeholder="Search item category..." />
          <Button @click="refresh()">Refresh</Button>
        </div>

        <div class="flex items-center justify-end gap-2">
          <NewItemCategoriesModal />
        </div>
      </div>

      <Table title="Item Categories Table" subtitle="List of item categories" :data-length="category.data?.length">
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th>Code</th>
            <th>Item Type</th>
            <th>Last Modified</th>
            <th>Actions</th>
          </tr>
        </Thead>
        <Tbody :data="category.data" :loading="category.loading" cols="6">
          <tr v-for="(item, index) in category.data" :key="item.id">
            <Td :data="index + 1" />
            <Td class="text-left" :data="item.name" />
            <Td :data="item.code" />
            <Td :data="itemTypeName(item.item_type_id)" />
            <Td :data="parse.dateTimeAgo(item.updated_at)" />
            <Td class="space-x-2">
              <EditItemCategoryModal :data="item" />
              <Button size="sm" class="hover:text-danger" @click="remove(item)">Delete</Button>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewItemCategoriesModal from '@/app/admin/item_categories/modals/NewItemCategoriesModal.vue'
import EditItemCategoryModal from '@/app/admin/item_categories/modals/UpdateItemCategoryModal.vue'

const category = useAdminItemCategoryStore()
const type = useAdminItemTypeStore()
const parse = useParser()
const pop = usePopup()

function itemTypeName(id: any) {
  return type.data?.find((itemType) => itemType.id === id)?.name ?? 'Unknown item type'
}

async function refresh() {
  await Promise.all([category.fetch(true), type.fetch(true)])
}

async function remove(item: ItemCategory) {
  const confirm = await pop.confirm({ text: `Are you sure you want to delete ${item.name}?` })
  if (!confirm.isConfirmed) return

  pop.load()
  try {
    const res = await category.remove(item)
    pop.success(res.message ?? 'Item category deleted successfully')
  } catch (error) {
    pop.unload()
    throw error
  }
}

onBeforeMount(async () => {
  await Promise.all([category.fetch(), type.fetch()])
})
</script>

<style scoped></style>
