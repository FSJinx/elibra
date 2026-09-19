<template>
  <div class="flex flex-col size-full">
    <SectionHeader title="Branch" description="Manage branches of the Isabela State University" icon="buildings" />

    <div class="flex-1 flex flex-col gap-3 p-5">
      <div class="flex items-center justify-end gap-2">
        <Input id="search-branch" class="max-w-100" placeholder="Search by branch name" />
        <AddNewBranch />
      </div>
      <Table title="Branch Table" subtitle="List of branches in ISU">
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th>Campus</th>
            <th>Status</th>
            <th>Last Modified</th>
            <th>Actions</th>
          </tr>
        </Thead>
        <Tbody :data="branch.data" :loading="branch.loading" cols="6">
          <tr class="hover" v-for="(c, index) in branch.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="c?.name" />
            <Td :data="c?.code" />
            <Td>
              <Status :variant="c?.status">{{ c?.status }}</Status>
            </Td>
            <Td :data="parse.formatDateAgo(c?.updated_at)" />
            <Td class="space-x-2">
              <!-- <UpdateCampusModal :data="c" /> -->
              <Button size="sm" variant="danger" @click="remove(c)">Delete</Button>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewBranch from '@/app/admin/branch/modals/AddNewBranch.vue'

const branch = useBranchStore()
const parse = useParser()
const pop = usePopup()

async function remove(c: Branch) {
  const res = await pop.confirm({ text: `Are you sure you want to delete ${c?.name}?` })

  if (res.isConfirmed) {
    pop.load()
    try {
      const res = await branch.remove(c)
      pop.success(res.message ?? 'Something is deleted')
    } catch (e) {
      throw e
    }
  }
}
</script>

<style scoped></style>
