<template>
  <div class="flex justify-end mb-3">
    <Button variant="primary">Create New</Button>
  </div>

  <Table name="Campus Table" @search="campus.getCampuses({ query: $event.target })" @refresh="refresh">
    <Thead>
      <tr>
        <th class="w-20">ID</th>
        <th class="text-left">Campus Name</th>
        <th>Code</th>
        <th class="w-50">Status</th>
      </tr>
    </Thead>

    <Tbody :loading="store.loading" :columns="4">
      <tr v-for="c in store.campuses">
        <td>{{ c.id }}</td>
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

const campusSearch = ref('')
const parse = useParser()

function refresh() {
  campusSearch.value = ''

  campus.getCampuses()
}
</script>

<style scoped></style>
