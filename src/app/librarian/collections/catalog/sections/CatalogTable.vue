<template>
  <!-- Catalog Table -->
  <Table :data-length="data?.length" title="Catalog List" subtitle="List of all library materials.">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th class="text-left max-w-10">Title</Th>
        <Th>Item Type</Th>
        <Th>Call Number</Th>
        <Th>OPAC</Th>
      </tr>
    </Thead>

    <Tbody :cols="5" :loading="loading" :data="data">
      <tr class="hover" v-for="(item, index) in data" :key="item.id" @click="view(item.id)">
        <Td :data="(index as number) + 1" />

        <Td class="text-left">
          <Title :level="4">{{ item.title }}</Title>

          <p class="text-xs text-foreground-secondary line-clamp-1 max-w-150">
            {{ item.subtitle ?? item.description ?? 'This item has no subtitle or description.' }}
          </p>
        </Td>

        <Td :data="item_type.byId(item.item_type_id)?.name" />
        <Td :data="item.call_number" />

        <Td :data="item.released ? 'True' : 'False'" />
      </tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
interface Props {
  data: any
  loading: boolean
}

const props = defineProps<Props>()
const item_type = useItemTypeStore()

function view(id: number) {
  return router.push({ name: 'librarian.collections.catalog.view', params: { id: id } })
}
</script>

<style scoped></style>
