<template>
  <div class="flex flex-col size-full">
    <SectionHeader title="Patron Types" description="Manage item types for item classification and advanced searching" icon="tags" />

    <div class="flex-1 flex flex-col gap-3 p-5">
      <div class="grid grid-cols-2 gap-2 p-5 bg-background border border-border rounded-xl">
        <div class="flex items-center gap-2">
          <Input id="search-item-type" class="max-w-100" placeholder="Search item type..." />
          <Button @click="search">Refresh</Button>
        </div>

        <div class="flex items-center justify-end gap-2">
          <NewItemTypeModal />
        </div>
      </div>

      <Table title="Patron Types Table" subtitle="List of all item types" data-length="">
        <Thead>
          <tr>
            <th>No.</th>
            <th>Key</th>
            <th class="text-left">Name</th>
            <th class="text-left">Description</th>
            <th>
              Account Validity Period
              <p class="font-normal lowercase">(in years)</p>
            </th>
            <th>Has Campus</th>
            <th>Has Department</th>
            <th>Has Program</th>
            <th>Last Modified</th>
            <th>Actions</th>
          </tr>
        </Thead>
        <Tbody :data="patron_type.data" :loading="loading" cols="10">
          <Tr v-for="(item, index) in patron_type.data" :key="index">
            <Td>{{ index + 1 }}</Td>
            <Td :data="item.key" />
            <Td class="text-left" :data="item.name" />
            <Td class="text-left" :data="item.description" />
            <Td :data="item.validity_period" />
            <Td>
              <Status :variant="item.has_campus">{{ item.has_campus }}</Status>
            </Td>
            <Td>
              <Status :variant="item.has_department">{{ item.has_department }}</Status>
            </Td>
            <Td>
              <Status :variant="item.has_program">{{ item.has_program }}</Status>
            </Td>
            <Td :data="parse.dateTimeAgo(item.updated_at)" />
            <Td>
              <Button size="sm">Edit</Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
const patron_type = patronTypeStore()

const loading = ref<boolean>(false)
const parse = useParser()

async function search() {
  loading.value = true

  try {
    await patron_type.fetch()
  } finally {
    loading.value = false
  }
}

onBeforeMount(() => search())
</script>

<style scoped></style>
