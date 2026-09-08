<template>
  <div class="size-full flex flex-col">
    <SectionHeader title="Check-out / Loans" description="Manage patrons' loans and returns." icon="laptop" class="bg-background border-b border-border">
      <div class="flex items-end justify-end">
        <Button variant="primary" icon="plus" @click="openNewLoan"> New loan </Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-5 p-5">
      <!-- Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard label="Active loans" :value="stats.active" icon="book-open" variant="default" />
        <StatCard label="Due today" :value="stats.dueToday" icon="clock" variant="warning" />
        <StatCard label="Overdue" :value="stats.overdue" icon="alert-triangle" variant="danger" />
        <StatCard label="Returned this week" :value="stats.returnedThisWeek" icon="check-circle" variant="success" />
      </div>

      <!-- Filters -->
      <Card>
        <Form>
          <template #body>
            <Control>
              <Input id="loans-search" placeholder="Search by borrower or item title..." v-model="filters.search" enable-clear />
              <Select id="loans-status" title="Status" v-model="filters.status">
                <Option value="">All statuses</Option>
                <Option value="active">Active</Option>
                <Option value="overdue">Overdue</Option>
                <Option value="returned">Returned</Option>
              </Select>
              <Select id="loans-branch" title="Branch" v-model="filters.branch">
                <Option value="">All branches</Option>
                <template v-for="item in branch.branches" :key="item.id">
                  <Option :value="item.id">{{ item.name }}</Option>
                </template>
              </Select>
              <Button @click="resetFilters">Reset</Button>
            </Control>
          </template>
        </Form>
      </Card>

      <Table>
        <Thead>
          <tr>
            <th class="text-left">Item</th>
            <th>Borrower</th>
            <th>Branch</th>
            <th>Borrowed</th>
            <th>Due date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </Thead>
        <Tbody :data="filteredLoans.length" :loading="false" :cols="7">
          <tr v-for="row in filteredLoans" :key="row.id">
            <Td class="text-left">
              <p class="font-medium">{{ row.item_title }}</p>
              <p class="text-xs text-muted-foreground">{{ row.item_sku }}</p>
            </Td>

            <Td>
              <p class="font-medium">{{ row.borrower_name }}</p>
              <p class="text-xs text-foreground-secondary">{{ row.borrower_id }}</p>
            </Td>

            <Td class="text-foreground-secondary">{{ row.branch }}</Td>

            <Td class="text-foreground-secondary">{{ formatDate(row.borrowed_at) }}</Td>

            <Td :class="loanStatus(row) === 'overdue' ? 'text-danger font-medium' : 'text-foreground-secondary'">
              {{ formatDate(row.due_at) }}
            </Td>

            <Td class="text-center">
              <Status class="text-xs capitalize px-3 py-1 rounded-full border border-current/30" :variant="statusVariant(loanStatus(row))">
                {{ loanStatus(row) }}
              </Status>
            </Td>

            <Td>
              <div class="inline-flex items-center gap-2">
                <Button v-if="loanStatus(row) !== 'returned'" size="sm" @click="renewLoan(row)"> Renew </Button>

                <Button v-if="loanStatus(row) !== 'returned'" size="sm" variant="primary" @click="returnLoan(row)"> Return </Button>
              </div>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
interface LoanRecord {
  id: number
  item_title: string
  item_sku: string
  borrower_name: string
  borrower_id: string
  branch: string
  borrowed_at: string
  due_at: string
  returned_at: string | null
}

const branch = useBranchStore()

// TODO: replace with real fetch (e.g. useLoans() composable)
const loans = reactive<LoanRecord[]>([
  { id: 1, item_title: 'Clean Architecture', item_sku: 'BK-00987', borrower_name: 'Juan Dela Cruz', borrower_id: 'STU-00231', branch: 'Main Branch', borrowed_at: '2026-08-20', due_at: '2026-09-03', returned_at: null },
  { id: 2, item_title: 'Noli Me Tangere', item_sku: 'BK-01102', borrower_name: 'Maria Santos', borrower_id: 'STU-00456', branch: 'Annex Branch', borrowed_at: '2026-08-25', due_at: '2026-09-08', returned_at: null },
  { id: 3, item_title: 'Introduction to Algorithms', item_sku: 'BK-00231', borrower_name: 'Pedro Reyes', borrower_id: 'STU-00789', branch: 'Main Branch', borrowed_at: '2026-08-15', due_at: '2026-08-29', returned_at: null },
  { id: 4, item_title: 'Calculus Vol. 1', item_sku: 'BK-01599', borrower_name: 'Ana Lopez', borrower_id: 'STU-00912', branch: 'Main Branch', borrowed_at: '2026-08-10', due_at: '2026-08-24', returned_at: '2026-08-22' },
])

const filters = reactive({
  search: '',
  status: '',
  branch: '',
})

function loanStatus(row: LoanRecord): 'active' | 'overdue' | 'returned' {
  if (row.returned_at) return 'returned'
  const isPastDue = new Date(row.due_at) < new Date()
  return isPastDue ? 'overdue' : 'active'
}

function statusVariant(status: string) {
  const map: Record<string, string> = {
    active: 'default',
    overdue: 'danger',
    returned: 'success',
  }
  return map[status] ?? 'default'
}

function formatDate(date: string | null) {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' })
}

const filteredLoans = computed(() =>
  loans.filter((row) => {
    const matchesSearch = !filters.search || row.item_title.toLowerCase().includes(filters.search.toLowerCase()) || row.borrower_name.toLowerCase().includes(filters.search.toLowerCase())

    const matchesStatus = !filters.status || loanStatus(row) === filters.status
    const matchesBranch = !filters.branch || row.branch === filters.branch

    return matchesSearch && matchesStatus && matchesBranch
  }),
)

const stats = computed(() => {
  const today = new Date().toDateString()

  return {
    active: loans.filter((l) => loanStatus(l) === 'active').length,
    dueToday: loans.filter((l) => !l.returned_at && new Date(l.due_at).toDateString() === today).length,
    overdue: loans.filter((l) => loanStatus(l) === 'overdue').length,
    returnedThisWeek: loans.filter((l) => {
      if (!l.returned_at) return false
      const daysSince = (Date.now() - new Date(l.returned_at).getTime()) / 86400000
      return daysSince <= 7
    }).length,
  }
})

function returnLoan(row: LoanRecord) {
  // TODO: call backend, e.g. await loanStore.returnItem(row.id)
  row.returned_at = new Date().toISOString().split('T')[0]
}

function renewLoan(row: LoanRecord) {
  // TODO: call backend, e.g. await loanStore.renew(row.id)
  const newDue = new Date(row.due_at)
  newDue.setDate(newDue.getDate() + 14)
  row.due_at = newDue.toISOString().split('T')[0]
}

function resetFilters() {
  filters.search = ''
  filters.status = ''
  filters.branch = ''
}

function openNewLoan() {
  // TODO: open modal/drawer for creating a new loan (borrower search + item scan)
}
</script>
