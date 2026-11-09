<template>
  <Table title="Available Copies" subtitle="">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th>Accession Number</Th>
        <Th>Status</Th>
        <Th>Actions</Th>
      </tr>
    </Thead>
    <Tbody :data="data?.accessions" :loading="false" error-message="No available copies yet">
      <Tr v-for="(item, index) in data?.accessions">
        <Td :data="index + 1" />
        <Td :data="item?.accession_number" />
        <Td class="capitalize" :data="item?.status" />
        <Td>No available actions yet</Td>
      </Tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
const props = defineProps<{ data: Opac | null }>()
</script>

<style scoped></style>
