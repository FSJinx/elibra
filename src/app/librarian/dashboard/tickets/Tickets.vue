<template>
  <div class="size-full flex flex-col">
    <SectionHeader title="Support Tickets" description="Track and resolve patron issues and requests." icon="ticket" class="bg-background border-b border-border">
      <div class="flex items-end justify-end">
        <Button variant="primary" icon="plus" @click="openNewTicket"> New ticket </Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-5 p-5 overflow-hidden">
      <!-- Quick filter bar -->
      <div class="flex flex-wrap items-center gap-3">
        <Input id="tickets-search" class="max-w-xs" placeholder="Search tickets..." v-model="filters.search" enable-clear />

        <Select id="tickets-priority" title="Priority" v-model="filters.priority" class="max-w-40">
          <Option value="">All priorities</Option>
          <Option value="urgent">Urgent</Option>
          <Option value="high">High</Option>
          <Option value="normal">Normal</Option>
          <Option value="low">Low</Option>
        </Select>

        <Select id="tickets-category" title="Category" v-model="filters.category" class="max-w-48">
          <Option value="">All categories</Option>
          <Option value="lost_item">Lost item</Option>
          <Option value="damaged_item">Damaged item</Option>
          <Option value="account">Account issue</Option>
          <Option value="technical">Technical</Option>
          <Option value="complaint">Complaint</Option>
          <Option value="other">Other</Option>
        </Select>

        <Button variant="text" @click="resetFilters">Clear filters</Button>

        <div class="ml-auto text-sm text-foreground-secondary">{{ filteredTickets.length }} tickets</div>
      </div>

      <!-- Kanban board -->
      <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4 overflow-y-auto">
        <div v-for="column in columns" :key="column.status" class="flex flex-col gap-3 min-h-0">
          <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
              <span class="size-2 rounded-full" :class="column.dotClass"></span>
              <h3 class="text-sm font-semibold">{{ column.label }}</h3>
            </div>
            <span class="text-xs text-foreground-secondary bg-secondary rounded-full px-2 py-0.5">
              {{ ticketsByStatus(column.status).length }}
            </span>
          </div>

          <div class="flex flex-col gap-2 overflow-y-auto pr-1">
            <Card v-for="ticket in ticketsByStatus(column.status)" :key="ticket.id" class="p-3! rounded-xl! cursor-pointer hover:shadow-md transition-all duration-200 border-l-4" :class="priorityBorderClass(ticket.priority)" @click="openTicket(ticket)">
              <div class="flex items-start justify-between gap-2">
                <p class="text-xs font-mono text-foreground-secondary">#{{ ticket.id }}</p>
                <Status class="text-[10px] capitalize px-2 py-0.5 rounded-full border border-current/30" :variant="priorityVariant(ticket.priority)">
                  {{ ticket.priority }}
                </Status>
              </div>

              <p class="mt-1.5 text-sm font-medium leading-snug line-clamp-2">{{ ticket.subject }}</p>

              <div class="mt-3 flex items-center justify-between text-xs text-foreground-secondary">
                <span class="inline-flex items-center gap-1">
                  <Icon icon="user" class="text-[11px]" />
                  {{ ticket.patron_name }}
                </span>
                <span>{{ formatRelativeDate(ticket.updated_at) }}</span>
              </div>
            </Card>

            <p v-if="ticketsByStatus(column.status).length === 0" class="text-xs text-foreground-secondary text-center py-6">No tickets</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail panel -->
    <div v-if="activeTicket" class="fixed inset-0 z-50 flex justify-end">
      <div class="absolute inset-0 bg-black/30" @click="closeTicket"></div>

      <div class="relative w-full max-w-md h-full bg-background border-l border-border p-5 overflow-y-auto flex flex-col gap-4">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-mono text-foreground-secondary">#{{ activeTicket.id }}</p>
            <h2 class="mt-1 text-lg font-semibold leading-snug">{{ activeTicket.subject }}</h2>
          </div>
          <button type="button" class="text-foreground-secondary hover:text-foreground" @click="closeTicket">
            <Icon icon="x" />
          </button>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <Status class="text-xs capitalize px-3 py-1 rounded-full border border-current/30" :variant="priorityVariant(activeTicket.priority)">
            {{ activeTicket.priority }}
          </Status>
          <Status class="text-xs capitalize px-3 py-1 rounded-full border border-current/30" variant="default">
            {{ formatCategory(activeTicket.category) }}
          </Status>
        </div>

        <div class="text-sm text-foreground-secondary">
          <p>
            <span class="font-medium text-foreground">{{ activeTicket.patron_name }}</span> · {{ activeTicket.patron_id }}
          </p>
          <p class="mt-1">Opened {{ formatRelativeDate(activeTicket.created_at) }}</p>
        </div>

        <div class="h-px bg-border"></div>

        <p class="text-sm leading-relaxed">{{ activeTicket.description }}</p>

        <div class="h-px bg-border"></div>

        <Control direction="col">
          <Label id="ticket-status">Move to</Label>
          <Select id="ticket-status" v-model="activeTicket.status" @update:modelValue="updateStatus(activeTicket)">
            <Option value="open">Open</Option>
            <Option value="in_progress">In Progress</Option>
            <Option value="resolved">Resolved</Option>
            <Option value="closed">Closed</Option>
          </Select>
        </Control>

        <Control direction="col">
          <Label id="ticket-reply">Add reply / note</Label>
          <Textarea id="ticket-reply" v-model="replyMessage" placeholder="Type your response..." :rows="4" />
        </Control>

        <Button variant="primary" class="w-full" @click="sendReply">Send reply</Button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Ticket {
  id: number
  subject: string
  description: string
  patron_name: string
  patron_id: string
  category: 'lost_item' | 'damaged_item' | 'account' | 'technical' | 'complaint' | 'other'
  priority: 'urgent' | 'high' | 'normal' | 'low'
  status: 'open' | 'in_progress' | 'resolved' | 'closed'
  created_at: string
  updated_at: string
}

// TODO: replace with real fetch (e.g. useTickets() composable + ticketStore)
const tickets = reactive<Ticket[]>([
  { id: 1042, subject: 'Lost my library card, need replacement', description: 'I misplaced my library card somewhere last week and need a new one issued.', patron_name: 'Juan Dela Cruz', patron_id: 'STU-00231', category: 'account', priority: 'normal', status: 'open', created_at: '2026-09-05T10:00:00', updated_at: '2026-09-05T10:00:00' },
  { id: 1041, subject: 'Book returned with water damage', description: 'The book "Clean Architecture" got wet in transit, pages are stuck together.', patron_name: 'Maria Santos', patron_id: 'STU-00456', category: 'damaged_item', priority: 'high', status: 'in_progress', created_at: '2026-09-04T09:00:00', updated_at: '2026-09-06T08:00:00' },
  { id: 1040, subject: 'Cannot log in to OPAC portal', description: 'Getting an "invalid credentials" error even though I reset my password twice.', patron_name: 'Pedro Reyes', patron_id: 'STU-00789', category: 'technical', priority: 'urgent', status: 'open', created_at: '2026-09-06T07:30:00', updated_at: '2026-09-06T07:30:00' },
  { id: 1039, subject: 'Unfair fine charged on returned book', description: 'I returned the book on time but was still charged a late fee. Please review.', patron_name: 'Ana Lopez', patron_id: 'STU-00912', category: 'complaint', priority: 'normal', status: 'resolved', created_at: '2026-09-02T13:00:00', updated_at: '2026-09-03T15:00:00' },
  { id: 1038, subject: 'Requesting extension for thesis research materials', description: 'Need additional 2 weeks for the reference materials due to ongoing research.', patron_name: 'Carlo Villanueva', patron_id: 'STU-01023', category: 'other', priority: 'low', status: 'closed', created_at: '2026-08-28T11:00:00', updated_at: '2026-08-30T09:00:00' },
])

const filters = reactive({
  search: '',
  priority: '',
  category: '',
})

const columns = [
  { status: 'open', label: 'Open', dotClass: 'bg-foreground-secondary' },
  { status: 'in_progress', label: 'In Progress', dotClass: 'bg-warning' },
  { status: 'resolved', label: 'Resolved', dotClass: 'bg-success' },
  { status: 'closed', label: 'Closed', dotClass: 'bg-danger' },
] as const

const activeTicket = ref<Ticket | null>(null)
const replyMessage = ref('')

const filteredTickets = computed(() =>
  tickets.filter((t) => {
    const matchesSearch = !filters.search || t.subject.toLowerCase().includes(filters.search.toLowerCase()) || t.patron_name.toLowerCase().includes(filters.search.toLowerCase())

    const matchesPriority = !filters.priority || t.priority === filters.priority
    const matchesCategory = !filters.category || t.category === filters.category

    return matchesSearch && matchesPriority && matchesCategory
  }),
)

function ticketsByStatus(status: string) {
  return filteredTickets.value.filter((t) => t.status === status)
}

function priorityVariant(priority: string) {
  const map: Record<string, string> = {
    urgent: 'danger',
    high: 'warning',
    normal: 'default',
    low: 'info',
  }
  return map[priority] ?? 'default'
}

function priorityBorderClass(priority: string) {
  const map: Record<string, string> = {
    urgent: 'border-l-danger',
    high: 'border-l-warning',
    normal: 'border-l-border',
    low: 'border-l-info',
  }
  return map[priority] ?? 'border-l-border'
}

function formatCategory(category: string) {
  return category.replace('_', ' ')
}

function formatRelativeDate(date: string) {
  const diffMs = Date.now() - new Date(date).getTime()
  const diffHrs = Math.floor(diffMs / 3600000)

  if (diffHrs < 1) return 'just now'
  if (diffHrs < 24) return `${diffHrs}h ago`
  return `${Math.floor(diffHrs / 24)}d ago`
}

function openTicket(ticket: Ticket) {
  activeTicket.value = ticket
  replyMessage.value = ''
}

function closeTicket() {
  activeTicket.value = null
}

function updateStatus(ticket: Ticket) {
  // TODO: call backend, e.g. await ticketStore.updateStatus(ticket.id, ticket.status)
  ticket.updated_at = new Date().toISOString()
}

function sendReply() {
  if (!replyMessage.value.trim() || !activeTicket.value) return
  // TODO: call backend, e.g. await ticketStore.reply(activeTicket.value.id, replyMessage.value)
  activeTicket.value.updated_at = new Date().toISOString()
  replyMessage.value = ''
  closeTicket()
}

function resetFilters() {
  filters.search = ''
  filters.priority = ''
  filters.category = ''
}

function openNewTicket() {
  // TODO: open modal/drawer for creating a new ticket
}
</script>
