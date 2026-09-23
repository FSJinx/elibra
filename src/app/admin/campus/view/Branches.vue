<template>
  <div class="size-full flex flex-col gap-5">
    <SectionHeader :title="campus.currentData?.name as string" :description="`Manage ${campus.currentData?.name}'s Branches`">
      <div class="flex items-start justify-end gap-2 p-2">
        <Button variant="primary" left-icon="plus-lg">Add New</Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-5">
      <Form>
        <Card>
          <Control>
            <Input id="" type="text" placeholder="Search by department name or dean..." class="max-w-125" />
          </Control>
        </Card>
      </Form>
      <Table title="Branches Table" :subtitle="`Manage branches under ${campus.currentData?.name}`">
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th class="text-left">Email</th>
            <th>Contact Info</th>
            <th>Last Modified</th>
          </tr>
        </Thead>
        <Tbody :data="branch.data" :loading="branch.loading" cols="5">
          <tr class="hover" v-for="(b, index) in branch.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="b?.name" />
            <Td class="text-left" :data="b?.email" />
            <Td :data="b?.contact_info" />
            <Td :data="parse.formatDateAgo(b?.updated_at)" />
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
const campus = useCampusStore()
const branch = useAdminBranchStore()
const parse = useParser()

onBeforeMount(async () => {
  await branch.fetch({ campus_id: campus.currentData?.id }, true)
})
</script>

<style scoped></style>
