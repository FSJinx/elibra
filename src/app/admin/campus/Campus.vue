<template>
  <Table name="Campus Table" description="List of Campuses in Isabela State University" @search="campus.params.query = $event" @refresh="campus.getCampuses(true)">
    <Thead>
      <tr>
        <th class="w-25">ID</th>
        <th class="text-left">Campus Name</th>
        <th>Code</th>
        <th>Status</th>
      </tr>
    </Thead>

    <Tbody :loading="store.loading" :columns="4">
      <tr v-for="c in store.campuses">
        <Td :data="c.id" />
        <td class="text-left">{{ c.name ?? 'No name' }}</td>
        <Td :data="c.code" />
        <td>
          <Badge :variant="parse.status(c?.status)">{{ parse.toCapital(c.status) }}</Badge>
        </td>
      </tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
const campus = useCampus()
const store = campusStore()

const parse = useParser()

onMounted(() => {
  if (!store.campuses) {
    campus.getCampuses()
  }
})

onBeforeUnmount(() => {
  campus.refresh()
})
</script>

<style scoped></style>
