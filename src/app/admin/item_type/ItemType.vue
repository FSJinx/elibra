<template>
  <!-- <PageConstruction/> -->

  <div class="size-full flex-1 flex flex-col">
    <SectionHeader title="Item Types" description="Manage item types for item classification and advanced searching" icon="tags" />

    <div class="flex-1 flex flex-col p-5 gap-5 overflow-hidden">
      <Card>
        <CardBody class="flex justify-between items-center">
          <Input id="search-item-type" class="max-w-100" placeholder="Search item type..." />

          <div class="flex items-center justify-end gap-2">
            <NewItemTypeModal />
          </div>
        </CardBody>
      </Card>

      <Table title="Item Types Table" subtitle="List of all item types" :data-length="item_type.data?.length ?? 0">
        <Thead>
          <tr>
            <Th>No.</Th>
            <Th class="text-left">Name</Th>
            <Th>Code</Th>
            <Th>Loanable</Th>
            <Th>Last Modified</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="item_type.data" :loading="item_type.loading" cols="6">
          <tr v-for="(type, index) in item_type.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="type.name" />
            <Td :data="type.slug" />
            <Td :data="type.loanable ? 'False' : 'True'" />
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
import NewItemTypeModal from '@/app/admin/item_type/modals/NewItemTypeModal.vue';

const item_type = useAdminItemTypeStore()
const parse = useParser()

onBeforeMount(async () => {
  await item_type.fetch()
})
</script>

<style scoped></style>
