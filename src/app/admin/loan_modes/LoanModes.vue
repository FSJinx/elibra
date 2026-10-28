<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Loan Modes" description="Manage loan modes for circulation" icon="cash-coin">
      <div class="flex items-center justify-end">
        <NewLoanMode @refresh="search" />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col overflow-y-auto p-5 gap-3">
      <Card class="">
        <Button class="ml-auto" @click="search">Refresh</Button>
      </Card>

      <Table title="Loan Modes" subtitle="These are the current loan modes your library accomodate" :data="paginatedData" cols="5">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th class="text-left">Name</Th>
            <Th>Key</Th>
            <Th class="text-left">Description</Th>
            <Th>Renewable</Th>
            <Th>Charges Fine</Th>
            <Th>Has Grace Period</Th>
            <Th>Active</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>

        <Tbody :data="loan_mode.data" :loading="loading">
          <Tr v-for="(item, index) in loan_mode.data" :key="item.id" :to="{ name: 'admin.loan_modes.view', params: { id: item.id } }">
            <Td :data="index + 1" />
            <Td class="text-left" :data="item.name" />
            <Td :data="item.slug" />
            <Td class="text-left" :data="item.description" />
            <Td>
              <Status :variant="item.renewable">{{ item.renewable }}</Status>
            </Td>
            <Td>
              <Status :variant="item.charges_fine">{{ item.charges_fine }}</Status>
            </Td>
            <Td>
              <Status :variant="item.has_grace_period">{{ item.has_grace_period }}</Status>
            </Td>
            <Td>
              <Status :variant="item.is_active">{{ item.is_active }}</Status>
            </Td>
            <Td class="space-x-2">
              <EditLoanMode :data="item" @refresh="search" />
              <Button class="hover:text-danger" size="sm" @click.stop="remove(item)">Delete</Button>
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
const pop = usePopup()

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

async function remove(item: LoanMode) {
  const confirmation = await pop.confirm({ text: `Are you sure you want to delete ${item.name}?` })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const res = await loan_mode.remove(item.id)
    pop.success(res.message ?? 'Loan mode deleted successfully')
  } catch (e: any) {
    pop.unload()
    pop.error(e?.response?.data?.message ?? 'Failed to delete loan mode')
    return
  }

  await search()
}

onBeforeMount(search)
</script>

<style scoped></style>
