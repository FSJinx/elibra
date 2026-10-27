<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Loan Modes" description="Manage loan modes for circulation" icon="cash-coin">
      <div class="flex items-center justify-end">
        <NewLoanMode @refresh="search" />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col overflow-y-auto p-5">
      <Table title="Loan Modes" subtitle="These are the current loan modes your library accomodate" :data="paginatedData" cols="5">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th class="text-left">Name</Th>
            <Th>Key</Th>
            <Th class="text-left">Description</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>

        <Tbody :data="loan_mode.data" :loading="loading">
          <Tr v-for="(item, index) in loan_mode.data" :key="index" :to="{ name: 'admin.loan_modes.view', params: { id: item.id } }">
            <Td :data="index + 1" />
            <Td class="text-left" :data="item.name" />
            <Td :data="item.slug" />
            <Td class="text-left" :data="item.description" />
            <Td class="space-x-2">
              <EditLoanMode :data="item" />
              <Button class="hover:text-danger" size="sm">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import EditLoanMode from '@/app/admin/loan_modes/modals/EditLoanMode.vue'
import NewLoanMode from '@/app/admin/loan_modes/modals/NewLoanMode.vue'

const loan_mode = loanModeStore()

const paginatedData = ref<Pagination>()
const loading = ref<boolean>(false)

async function search() {
  loading.value = true
  try {
    const res = await loan_mode.fetch()
    paginatedData.value = res.data
  } catch (e: any) {
    throw e
  } finally {
    loading.value = false
  }
}
onBeforeMount(search)
</script>

<style scoped></style>
