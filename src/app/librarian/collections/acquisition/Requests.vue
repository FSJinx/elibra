<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Acquisition Requests" description="Manage your library's acquisition requests" icon="box-arrow-in-down" />

    <div class="flex-1 flex p-5 overflow-hidden">
      <Table title="Recent Requests" subtitle="Manage your library's recent acquisition requests">
        <Thead>
          <tr>
            <Th>Request ID</Th>
            <Th>Requested By</Th>
            <Th>Title</Th>
            <Th>Author</Th>
            <Th>Priority</Th>
            <Th>Unit Price</Th>
            <Th>Net Price</Th>
            <Th>Preferred Supplier</Th>
            <Th>Status</Th>
          </tr>
        </Thead>

        <Tbody :data="data" :loading="false" :cols="9">
          <tr v-for="item in data" :key="item.id">
            <Td> #{{ item.id.toString().padStart(5, '0') }} </Td>

            <Td>
              <div class="flex flex-col">
                <span class="font-medium text-foreground">
                  {{ item.requestedBy.name }}
                </span>

                <span class="text-xs text-foreground-secondary">
                  {{ item.requestedBy.type }}
                </span>
              </div>
            </Td>

            <Td>
              <div class="max-w-60">
                <span class="font-medium text-foreground line-clamp-2" :title="item.title">
                  {{ item.title }}
                </span>
              </div>
            </Td>

            <Td>
              {{ item.author }}
            </Td>

            <Td>
              <Badge :variant="priorityVariant(item.priority)">
                {{ item.priority }}
              </Badge>
            </Td>

            <Td>
              {{ formatCurrency(item.unitPrice) }}
            </Td>

            <Td>
              {{ formatCurrency(item.netPrice) }}
            </Td>

            <Td>
              {{ item.preferredSupplier }}
            </Td>

            <Td>
              <Badge :variant="statusVariant(item.status)">
                {{ item.status }}
              </Badge>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import SectionHeader from '@/components/my/SectionHeader.vue'

interface AcquisitionRequest {
  id: number
  requestedBy: {
    name: string
    type: string
  }
  title: string
  author: string
  priority: 'Low' | 'Normal' | 'High' | 'Urgent'
  unitPrice: number
  netPrice: number
  preferredSupplier: string
  status: 'Pending' | 'Approved' | 'Rejected' | 'Ordered' | 'Received' | 'Cancelled'
}

const data: AcquisitionRequest[] = [
  {
    id: 1,
    requestedBy: {
      name: 'Juan Dela Cruz',
      type: 'Student',
    },
    title: 'The Pragmatic Programmer',
    author: 'David Thomas, Andrew Hunt',
    priority: 'High',
    unitPrice: 2500,
    netPrice: 7500,
    preferredSupplier: 'National Book Store',
    status: 'Pending',
  },
  {
    id: 2,
    requestedBy: {
      name: 'Maria Santos',
      type: 'Faculty',
    },
    title: 'Database System Concepts',
    author: 'Abraham Silberschatz',
    priority: 'Normal',
    unitPrice: 3200,
    netPrice: 6400,
    preferredSupplier: 'C&E Publishing',
    status: 'Approved',
  },
  {
    id: 3,
    requestedBy: {
      name: 'Pedro Garcia',
      type: 'Student',
    },
    title: 'Clean Code: A Handbook of Agile Software Craftsmanship',
    author: 'Robert C. Martin',
    priority: 'Urgent',
    unitPrice: 2800,
    netPrice: 8400,
    preferredSupplier: 'Fully Booked',
    status: 'Ordered',
  },
  {
    id: 4,
    requestedBy: {
      name: 'Ana Reyes',
      type: 'Faculty',
    },
    title: 'Computer Networking: A Top-Down Approach',
    author: 'James Kurose, Keith Ross',
    priority: 'Normal',
    unitPrice: 3500,
    netPrice: 7000,
    preferredSupplier: 'National Book Store',
    status: 'Received',
  },
  {
    id: 5,
    requestedBy: {
      name: 'Carlos Mendoza',
      type: 'Student',
    },
    title: 'Introduction to Algorithms',
    author: 'Thomas H. Cormen et al.',
    priority: 'High',
    unitPrice: 4500,
    netPrice: 9000,
    preferredSupplier: 'Amazon',
    status: 'Pending',
  },
  {
    id: 6,
    requestedBy: {
      name: 'Sofia Ramos',
      type: 'Faculty',
    },
    title: 'Artificial Intelligence: A Modern Approach',
    author: 'Stuart Russell, Peter Norvig',
    priority: 'High',
    unitPrice: 4200,
    netPrice: 12600,
    preferredSupplier: 'C&E Publishing',
    status: 'Approved',
  },
  {
    id: 7,
    requestedBy: {
      name: 'Miguel Torres',
      type: 'Student',
    },
    title: 'Web Development with Laravel',
    author: 'Matt Stauffer',
    priority: 'Normal',
    unitPrice: 2200,
    netPrice: 4400,
    preferredSupplier: 'Fully Booked',
    status: 'Rejected',
  },
  {
    id: 8,
    requestedBy: {
      name: 'Isabella Cruz',
      type: 'Faculty',
    },
    title: 'Fundamentals of Software Engineering',
    author: 'Carlo Ghezzi',
    priority: 'Low',
    unitPrice: 3000,
    netPrice: 3000,
    preferredSupplier: 'National Book Store',
    status: 'Cancelled',
  },
]

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(value)
}

const priorityVariant = (priority: AcquisitionRequest['priority']) => {
  switch (priority) {
    case 'Urgent':
      return 'danger'
    case 'High':
      return 'warning'
    case 'Normal':
      return 'primary'
    case 'Low':
      return 'secondary'
  }
}

const statusVariant = (status: AcquisitionRequest['status']) => {
  switch (status) {
    case 'Pending':
      return 'warning'
    case 'Approved':
      return 'success'
    case 'Ordered':
      return 'primary'
    case 'Received':
      return 'success'
    case 'Rejected':
      return 'danger'
    case 'Cancelled':
      return 'secondary'
  }
}
</script>
