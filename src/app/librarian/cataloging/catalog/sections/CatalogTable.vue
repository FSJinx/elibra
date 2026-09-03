<template>
  <!-- Catalog Table -->
  <Table :data-length="data.length" title="Catalog List" subtitle="List of all library materials.">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th class="text-left">Title</Th>
        <Th>Call Number</Th>
        <Th>Publication Year</Th>
      </tr>
    </Thead>

    <Tbody :columns="4" :loading="loading" :data="data">
      <tr class="hover" v-for="(item, index) in data" :key="item.id" @click="view(item.id)">
        <Td :data="(index as number) + 1" />

        <Td class="text-left">
          <p class="text-lg font-medium">
            {{ item.title }}
          </p>

          <p v-if="item.subtitle" class="text-sm text-foreground-secondary">
            {{ item.subtitle }}
          </p>
        </Td>

        <Td :data="item.call_number" />

        <Td :data="item.publication_year" />
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

function view(id: number) {
  return router.push({ name: 'librarian.catalog.view', params: { id: id } })
}
</script>

<style scoped></style>
