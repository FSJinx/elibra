<template>
  <!-- <PageConstruction/> -->

  <div class="size-full flex-1 flex flex-col">
    <SectionHeader title="Item Categories" description="Manage item categories of each item types for item classification and advanced searching" icon="collection" />

    <div class="flex-1 flex flex-col p-5 gap-5 overflow-hidden">
      <Card>
        <CardBody class="flex justify-between items-center">
          <Input id="search-item-category" class="max-w-100" placeholder="Search item category..." />

          <div class="flex items-center justify-end gap-2">
            <NewItemCategoriesModal />
          </div>
        </CardBody>
      </Card>

      <Table title="Item Types Table" subtitle="List of all item types" :data-length="category.data?.length ?? 0">
        <Thead>
          <tr>
            <Th>No.</Th>
            <Th class="text-left">Name</Th>
            <Th>Code</Th>
            <Th>Item Type</Th>
            <Th>Last Modified</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="category.data" :loading="category.loading" cols="6">
          <tr v-for="(type, index) in category.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="type.name" />
            <Td :data="type.code" />
            <Td :data="item_type(type.item_type_id)?.name" />
            <Td :data="parse.dateTimeAgo(type.updated_at)" />
            <Td class="space-x-2">
              <Button size="sm">Edit</Button>
              <Button size="sm">Delete</Button>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewItemCategoriesModal from '@/app/admin/item_categories/modals/NewItemCategoriesModal.vue'

const category = useAdminItemCategoryStore()
const type = useAdminItemTypeStore()
const parse = useParser()

const item_type = computed(() => {
  return (id: any) => type.data?.find((t) => t.id === id)
})

onBeforeMount(async () => {
  await category.fetch()
})
</script>

<style scoped></style>
