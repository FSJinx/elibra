<template>
  <div class="flex-1 flex flex-col gap-5 overflow-hidden p-5">
    <Card>
      <CardBody class="flex items-center gap-2">
        <Input class="max-w-100" id="" type="text" placeholder="Search section by section name" />

        <div class="ml-auto">
          <Button>New Section</Button>
        </div>
      </CardBody>
    </Card>

    <Table title="Library Sections" subtitle="These are the sections / departments of this library" data-length="2">
      <Thead>
        <tr>
          <Th>No</Th>
          <Th>Name</Th>
        </tr>
      </Thead>
      <Tbody :data="sections.data" :loading="sections.loading" cols="2">
        <Tr to="" v-for="(section, index) in sections.data" :key="index">
          <Td>{{ index + 1 }}</Td>
          <Td>{{ section.name }}</Td>
        </Tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
const sections = sectionStore()
const library = libraryStore()

onBeforeMount(() => {
  sections.fetch(library.currentData?.id)
})
</script>

<style scoped></style>
