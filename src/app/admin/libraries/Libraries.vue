<template>
  <div class="flex flex-col size-full overflow-hidden">
    <SectionHeader title="Libraries" description="Manage libraries in the Isabela State University" icon="building" />

    <div class="flex-1 flex flex-col gap-3 p-5">
      <div class="flex items-center justify-end gap-2">
        <Input id="search-library" class="max-w-100" placeholder="Search by library name" />
        <AddNewLibraryModal />
      </div>
      <Table title="Branch Table" subtitle="List of branches in ISU">
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th>Campus</th>
            <th>Phone</th>
            <th class="text-left">Email</th>
            <th>Last Modified</th>
            <th>Actions</th>
          </tr>
        </Thead>
        <Tbody :data="library.data" :loading="library.loading" cols="6">
          <tr class="hover" v-for="(b, index) in library.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="b?.name" />
            <Td :data="campus.getCampus(b?.campus_id)?.name ?? null" />
            <Td :data="b?.phone" />
            <Td :data="b.email ?? null" class="text-left"></Td>
            <Td :data="parse.formatDateAgo(b?.updated_at)" />
            <Td class="space-x-2">
              <EditLibraryModal :data="b" />
              <Button size="sm" variant="danger" @click="remove(b)">Delete</Button>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewLibraryModal from '@/app/admin/libraries/modals/AddNewLibraryModal.vue'
import EditLibraryModal from '@/app/admin/libraries/modals/EditLibraryModal.vue'

const library = useLibraryStore()
const campus = useCampusStore()
const parse = useParser()
const pop = usePopup()
const filters = reactive({})

async function remove(c: Library) {
  const res = await pop.confirm({ text: `Are you sure you want to delete ${c?.name}?` })

  if (res.isConfirmed) {
    pop.load()
    try {
      const res = await library.remove(c)
      pop.success(res.message ?? 'Something is deleted')
    } catch (e) {
      throw e
    }
  }
}
</script>

<style scoped></style>
