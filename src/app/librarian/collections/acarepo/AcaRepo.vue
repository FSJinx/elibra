<template>
  <div class="flex flex-col size-full overflow-hidden">
    <!-- Header -->
    <SectionHeader title="AcaRepo" description="AcaRepo: Academic Repository" icon="mortarboard">
      <div class="flex items-center justify-end gap-2">
        <NewInventory />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <!-- <Card>
        <CardBody> </CardBody>
      </Card> -->

      <Table title="Submissions" subtitle="Track theses submitted for approval.">
        <Thead>
          <Th>No.</Th>
          <Th class="text-left">Title</Th>
          <Th>Category</Th>
          <Th>Submitted By</Th>
          <Th>Date Submitted</Th>
          <Th>Status</Th>
          <Th>Action</Th>
        </Thead>

        <Tbody cols="7" :loading="acarepo.loading" :data="acarepo.data">
          <tr v-for="(item, index) in acarepo.data" :key="item.id" class="border-b border-border last:border-0 hover:bg-secondary/30 transition-colors">
            <Td :data="index + 1"></Td>
            <Td :data="item.title"></Td>
            <Td :data="item.category"></Td>
            <Td :data="item.doi"></Td>
          </tr>

          <tr v-if="acarepo.data?.length === 0">
            <Td colspan="8" class="px-5 py-10 text-center text-sm">No items match your filters.</Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
const acarepo = acaRepoStore()
</script>
