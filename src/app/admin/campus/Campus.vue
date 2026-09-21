<template>
  <div class="flex flex-col size-full">
    <SectionHeader title="Campus" description="Manage campuses of the Isabela State University" icon="buildings" />

    <div class="flex-1 flex flex-col gap-3 p-5">
      <div class="flex items-center justify-end gap-2">
        <Input id="search-campus" class="max-w-100" placeholder="Search by campus name" />
        <AddNewCampusModal />
      </div>
      <Table title="Campus Table" subtitle="List of campuses in ISU">
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th>Campus Code</th>
            <th>Status</th>
            <th>Last Modified</th>
            <th>Actions</th>
          </tr>
        </Thead>
        <Tbody :data="campus.data" :loading="campus.loading" cols="6">
          <tr class="hover" v-for="(c, index) in campus.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="c?.name" />
            <Td :data="c?.code" />
            <Td>
              <Status :variant="c?.status">{{ c?.status }}</Status>
            </Td>
            <Td :data="parse.formatDateAgo(c?.updated_at)" />
            <Td class="space-x-2">
              <UpdateCampusModal :data="c" />
              <Button size="sm" variant="danger" @click="remove(c)">Delete</Button>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewCampusModal from '@/app/admin/campus/modals/AddNewCampusModal.vue'
import UpdateCampusModal from '@/app/admin/campus/modals/UpdateCampusModal.vue'

const campus = useCampusStore()
const parse = useParser()
const pop = usePopup()

async function remove(c: Campus) {
  const res = await pop.confirm({text: `Are you sure you want to delete ${c?.name}?`})

  if (res.isConfirmed) {
    pop.load()
    try {
      const res = await campus.remove(c)
      pop.success(res.message ?? 'Something is deleted')
    } catch (e) {
      throw e
    }
  }
}
</script>

<style scoped></style>
