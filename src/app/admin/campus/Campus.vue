<template>
  <div class="flex flex-col size-full">
    <SectionHeader title="Campus" description="Manage campuses of the Isabela State University" icon="buildings" />

    <div class="flex-1 flex flex-col gap-3 p-5">
      <div class="grid grid-cols-2 gap-2 p-5 bg-background border border-border rounded-xl">
        <div class="flex items-center gap-2">
          <Input id="search-campus" class="max-w-100" placeholder="Search by campus name" />
          <Button @click="campus.fetch(true)">Refresh</Button>
        </div>

        <div class="flex items-center justify-end gap-2">
          <AddNewCampusModal />
          <RecentlyDeletedCampusModal />
        </div>
      </div>
      <Table title="Campus Table" subtitle="List of campuses in ISU" :data-length="campus.data?.length">
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th>Campus Code</th>
            <th>Status</th>
            <th>Last Modified</th>
          </tr>
        </Thead>
        <Tbody :data="campus.data" :loading="campus.loading" cols="5">
          <tr class="hover" v-for="(c, index) in campus.data" :key="index" @click="router.push({ name: 'admin.campus.show', params: { id: c.id } })">
            <Td :data="index + 1" />
            <Td class="text-left" :data="c?.name" />
            <Td :data="c?.code" />
            <Td>
              <Status :variant="c?.status">{{ c?.status }}</Status>
            </Td>
            <Td :data="parse.formatDateAgo(c?.updated_at)" />
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewCampusModal from '@/app/admin/campus/modals/AddNewCampusModal.vue'
import RecentlyDeletedCampusModal from '@/app/admin/campus/modals/RecentlyDeletedCampusModal.vue'
import UpdateCampusModal from '@/app/admin/campus/modals/UpdateCampusModal.vue'

const campus = useCampusStore()
const parse = useParser()
const pop = usePopup()
</script>

<style scoped></style>
