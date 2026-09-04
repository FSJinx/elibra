<template>
  <div class="flex size-full min-h-0 flex-col gap-5 p-5">
    <!-- Circulation Workspace -->
    <section class="overflow-hidden rounded-xl border border-border/70 bg-background">
      <!-- Section Header -->
      <div class="flex flex-col gap-3 border-b border-border/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-sm font-semibold text-foreground">Circulation</h2>

          <p class="mt-0.5 text-xs text-foreground-secondary">Process borrowing, returns, and renewals.</p>
        </div>

        <Badge variant="success"> Ready </Badge>
      </div>

      <div class="p-5">
        <!-- Patron Search -->
        <div class="space-y-2">
          <label for="patron-search" class="text-sm font-semibold text-foreground"> Patron </label>

          <div class="flex gap-2">
            <Input id="patron-search" v-model="patronQuery" placeholder="Scan or search patron ID..." left-icon="person" enable-clear class="flex-1" />

            <Button variant="default" left-icon="qr-code-scan"> Scan </Button>
          </div>
        </div>

        <!-- Selected Patron -->
        <div v-if="patron" class="mt-5 rounded-lg border border-border bg-muted/40 p-4">
          <div class="flex items-center gap-3">
            <!-- Avatar -->
            <div class="flex size-11 shrink-0 items-center justify-center rounded-full border border-border bg-background text-sm font-semibold text-primary">
              {{ patron.initials }}
            </div>

            <!-- Identity -->
            <div class="min-w-0 flex-1">
              <div class="flex flex-wrap items-center gap-2">
                <h3 class="truncate text-sm font-semibold">
                  {{ patron.name }}
                </h3>

                <Badge variant="default">
                  {{ patron.type }}
                </Badge>
              </div>

              <p class="mt-0.5 text-xs text-foreground-secondary">
                {{ patron.id }}
              </p>
            </div>

            <!-- Loan Count -->
            <div class="hidden text-right sm:block">
              <p class="text-lg font-semibold">
                {{ patron.activeLoans }}
              </p>

              <p class="text-xs text-foreground-secondary">Active loans</p>
            </div>
          </div>
        </div>

        <!-- Item Scanner -->
        <div class="mt-6 space-y-2">
          <label for="item-search" class="text-sm font-semibold text-foreground"> Item </label>

          <div class="flex gap-2">
            <Input id="item-search" v-model="itemQuery" placeholder="Scan accession number or search item..." left-icon="barcode" enable-clear class="flex-1" :disabled="!patron" />

            <Button variant="primary" left-icon="barcode-scan" :disabled="!patron"> Scan Item </Button>
          </div>

          <p v-if="!patron" class="text-xs text-foreground-secondary">Select a patron first before scanning an item.</p>
        </div>
      </div>
    </section>

    <!-- Current Loans -->
    <section class="overflow-hidden rounded-xl border border-border/70 bg-background">
      <div class="flex items-center justify-between border-b border-border/70 px-5 py-4">
        <div>
          <h2 class="text-sm font-semibold">Current Loans</h2>

          <p class="mt-0.5 text-xs text-foreground-secondary">Items currently borrowed by the selected patron.</p>
        </div>

        <span class="text-xs text-foreground-secondary"> {{ loans.length }} items </span>
      </div>

      <!-- Empty State -->
      <div v-if="!patron" class="flex min-h-45 flex-col items-center justify-center px-5">
        <div class="mb-3 flex size-10 items-center justify-center rounded-full bg-muted text-foreground-secondary">
          <Icon icon="person" />
        </div>

        <p class="text-sm font-medium">No patron selected</p>

        <p class="mt-1 text-xs text-foreground-secondary">Search or scan a patron to view their loans.</p>
      </div>

      <!-- No Loans -->
      <div v-else-if="!loans.length" class="flex min-h-45 flex-col items-center justify-center px-5">
        <p class="text-sm font-medium">No active loans</p>

        <p class="mt-1 text-xs text-foreground-secondary">This patron currently has no borrowed items.</p>
      </div>

      <!-- Loans -->
      <div v-else class="divide-y divide-border/60">
        <div v-for="loan in loans" :key="loan.id" class="flex items-center gap-4 px-5 py-4">
          <!-- Book Icon -->
          <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground-secondary">
            <Icon icon="book" />
          </div>

          <!-- Item -->
          <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-semibold">
              {{ loan.title }}
            </p>

            <p class="mt-0.5 text-xs text-foreground-secondary">
              {{ loan.callNumber }}
              <span class="mx-1">•</span>
              Accession {{ loan.accession }}
            </p>
          </div>

          <!-- Due Date -->
          <div class="hidden text-right sm:block">
            <p class="text-xs text-foreground-secondary">Due date</p>

            <p class="text-sm font-medium" :class="loan.overdue ? 'text-danger' : 'text-foreground'">
              {{ loan.dueDate }}
            </p>
          </div>

          <!-- Action -->
          <Button variant="default" size="sm" left-icon="arrow-return-left"> Return </Button>
        </div>
      </div>
    </section>

    <!-- Recent Transactions -->
    <section class="overflow-hidden rounded-xl border border-border/70 bg-background">
      <div class="border-b border-border/70 px-5 py-4">
        <h2 class="text-sm font-semibold">Recent Transactions</h2>

        <p class="mt-0.5 text-xs text-foreground-secondary">Latest circulation activity recorded by the library.</p>
      </div>

      <Table>
        <Thead>
          <tr>
            <Th class="text-left">Patron</Th>
            <Th class="text-left">Item</Th>
            <Th>Action</Th>
            <Th>Date</Th>
            <Th>Librarian</Th>
          </tr>
        </Thead>

        <Tbody :columns="5" :loading="loading" :data="transactions">
          <tr v-for="transaction in transactions" :key="transaction.id">
            <Td class="text-left">
              <div>
                <p class="text-sm font-medium">
                  {{ transaction.patron }}
                </p>

                <p class="text-xs text-foreground-secondary">
                  {{ transaction.patronId }}
                </p>
              </div>
            </Td>

            <Td class="text-left">
              <p class="text-sm font-medium">
                {{ transaction.item }}
              </p>

              <p class="text-xs text-foreground-secondary">
                {{ transaction.accession }}
              </p>
            </Td>

            <Td>
              <Badge :variant="transaction.action === 'Borrowed' ? 'primary' : 'success'">
                {{ transaction.action }}
              </Badge>
            </Td>

            <Td :data="transaction.date" />

            <Td :data="transaction.librarian" />
          </tr>
        </Tbody>
      </Table>
    </section>
  </div>
</template>

<script setup lang="ts">
interface Patron {
  id: string
  name: string
  initials: string
  type: string
  activeLoans: number
}

interface Loan {
  id: number
  title: string
  callNumber: string
  accession: string
  dueDate: string
  overdue: boolean
}

interface Transaction {
  id: number
  patron: string
  patronId: string
  item: string
  accession: string
  action: 'Borrowed' | 'Returned'
  date: string
  librarian: string
}

const patronQuery = ref('')
const itemQuery = ref('')
const loading = ref(false)

const patron = ref<Patron | null>(null)

const loans = ref<Loan[]>([])

const transactions = ref<Transaction[]>([
  {
    id: 1,
    patron: 'Maria Santos',
    patronId: '2023-10421',
    item: 'Database System Concepts',
    accession: 'ACC-2025-00124',
    action: 'Borrowed',
    date: 'Sep 01, 2026',
    librarian: 'J. Reyes',
  },
  {
    id: 2,
    patron: 'Juan Dela Cruz',
    patronId: '2022-08321',
    item: 'Clean Code',
    accession: 'ACC-2025-00087',
    action: 'Returned',
    date: 'Sep 01, 2026',
    librarian: 'A. Garcia',
  },
])
</script>
