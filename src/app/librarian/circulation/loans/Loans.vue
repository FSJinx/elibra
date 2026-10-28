<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Loans" description="Manage patrons' loans and returns." icon="laptop">
      <!-- <div class="flex items-center justify-end gap-2 text-sm">
        <router-link :to="{ name: link.path }" v-for="link in links" class="px-4 py-1 rounded-md border border-transparent transition-all duration-150" exact-active-class="bg-primary-soft/25 text-primary border-primary/50!">
          {{ link.name }}
        </router-link>
      </div> -->
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3">
      <Issue @refresh="search" />

      <Card>
        <CardBody class="flex items-center gap-2 justify-between">
          <Form class="flex items-center gap-3 w-full max-w-150 mr-auto" id="loan-search" @submit="search">
            <Input id="" type="text" placeholder="Search by accession number, patron name, or patron id" v-model="params.query" />
            <Button form="loan-search" type="submit">Search</Button>
          </Form>

          <Select class="max-w-max" v-model="params.sort">
            <Option value="">Default</Option>
            <Option value="status">Status</Option>
          </Select>

          <Select class="max-w-max" v-model="params.order">
            <Option value="asc">Ascending</Option>
            <Option value="desc">Descending</Option>
          </Select>
        </CardBody>
      </Card>

      <Table title="Current Loans" subtitle="These are the recent check outs of your library" :data="pagination" @per_page="params.per_page = $event" @next="params.page = $event" @prev="params.page = $event">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession</Th>
            <Th class="text-left">Title</Th>
            <Th class="text-left">Borrower</Th>
            <Th>Borrowed At</Th>
            <Th>Due Date</Th>
            <Th>Status</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="loan.data" :loading="loading">
          <Tr v-for="(item, index) in loan.data" :key="index">
            <Td :data="index + 1" />
            <Td :data="item.accession?.accession_number" />
            <Td class="text-left" :data="item.accession?.item?.title" />
            <Td class="text-left">{{ item.patron?.user?.name?.full }} ({{ item.patron?.user?.username }})</Td>
            <Td :data="parse.formatDateTime(item.borrowed_at)" />
            <Td :data="parse.formatDateTime(item.due_at)" />
            <Td>
              <Status :variant="item?.status">{{ item?.status }}</Status>
            </Td>
            <Td class="space-x-2">
              <!-- <EditLoanStatus :data="item" @refresh="search" /> -->
              <Button v-if="canUpdateLoan(item) && item.loan_mode?.renewable" variant="info" size="sm" :loading="actionId === item.id && actionType === 'renew'" :disabled="actionId !== null" @click="updateLoan(item, 'renew')"> Renew </Button>
              <Button v-if="canUpdateLoan(item)" variant="success" size="sm" :loading="actionId === item.id && actionType === 'return'" :disabled="actionId !== null" @click="updateLoan(item, 'return')"> Return </Button>
              <Button class="hover:text-danger" size="sm" :loading="deletingId === item.id" @click="deleteLoan(item)"> Delete </Button>
            </Td>
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import Issue from '@/app/librarian/circulation/loans/issue/Issue.vue'
import EditLoanStatus from '@/app/librarian/circulation/loans/modals/EditLoanStatus.vue'

const loan = loanStore()
const parse = useParser()
const auth = authStore()
const pop = usePopup()

const loading = ref<boolean>(false)
const deletingId = ref<Loan['id'] | null>(null)
const actionId = ref<Loan['id'] | null>(null)
const actionType = ref<'renew' | 'return' | null>(null)
const pagination = ref<Pagination>()

const defaultParams = (): Partial<LoanParams> => ({
  query: '',
  order: 'asc',
  library_id: auth.user?.library?.id ?? null,
})
const params = ref(defaultParams())

async function search() {
  loading.value = true
  try {
    const res = await loan.fetch(params.value)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

function canUpdateLoan(item: Loan) {
  return ['borrowed', 'overdue'].includes(item.status)
}

async function updateLoan(item: Loan, action: 'renew' | 'return') {
  const isReturn = action === 'return'
  const confirmation = await pop.confirm({
    title: isReturn ? 'Return this item?' : 'Renew this loan?',
    text: isReturn
      ? 'The loan will be marked returned and the item will become available.'
      : 'The due date will be extended by the loan period set for this patron type and loan mode.',
    confirmButtonText: isReturn ? 'Return item' : 'Renew loan',
  })

  if (!confirmation.isConfirmed) return

  actionId.value = item.id
  actionType.value = action
  pop.load()
  try {
    const res = isReturn ? await loan.returnLoan(item.id) : await loan.renew(item.id)
    pop.unload()
    await search()
    await pop.success(res.message ?? (isReturn ? 'Loan returned successfully.' : 'Loan renewed successfully.'))
  } catch (e: any) {
    pop.unload()
    const firstValidationError = e?.response?.data?.errors
      ? Object.values(e.response.data.errors).flat()[0]
      : null
    await pop.error(firstValidationError ?? e?.response?.data?.message ?? `Unable to ${action} this loan.`)
  } finally {
    actionId.value = null
    actionType.value = null
  }
}

async function deleteLoan(item: Loan) {
  const confirmation = await pop.confirm({
    title: 'Delete loan record?',
    text: 'This will remove the circulation from the current loans list. If the item is still checked out, it will be marked available again.',
    confirmButtonText: 'Delete',
  })

  if (!confirmation.isConfirmed) return

  deletingId.value = item.id
  pop.load()
  try {
    const res = await loan.deleteLoan(item.id)
    pop.unload()
    await search()
    await pop.success(res.message ?? 'Loan record deleted successfully.')
  } catch (e: any) {
    pop.unload()
    pop.error(e?.response?.data?.message ?? 'Unable to delete the loan record.')
  } finally {
    deletingId.value = null
  }
}

watch(params, search, { deep: true })

onBeforeMount(search)
</script>

<style scoped></style>
