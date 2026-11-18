<template>
  <Modal ref="modal" size="2xlarge">
    <ModalHeader use-default-layout :title="patronName" :subtitle="`Borrowing history · ${patronType}`" icon="clock-history" />
    <ModalBody>
      <Table class="border-0!" title="Borrowing History" subtitle="Latest transactions appear first" :data="pagination">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th class="text-left">Title</Th>
            <Th>Accession</Th>
            <Th>Loan Mode</Th>
            <Th>Borrowed</Th>
            <Th>Due</Th>
            <Th>Status</Th>
            <Th>Action</Th>
          </tr>
        </Thead>
        <Tbody :data="history" :loading="loading" cols="8" error-message="No borrowing history found for this patron.">
          <Tr v-for="(loan, index) in history" :key="loan.id">
            <Td :data="index + 1" />
            <Td class="text-left" :data="loan.accession?.item?.title" />
            <Td :data="loan.accession?.accession_number" />
            <Td :data="loan.loan_mode?.name" />
            <Td :data="parse.formatDateTime(loan.borrowed_at)" />
            <Td :data="parse.formatDate(loan.due_at)" />
            <Td>
              <Status :variant="loan.status">{{ loan.status }}</Status>
            </Td>
            <Td class="space-x-2">
              <ReturnButton :data="loan" @refresh="refreshHistory" v-if="['borrowed', 'overdue'].includes(loan.status)" />
              <RenewButton :data="loan" @refresh="refreshHistory" v-if="loan.status === 'borrowed'" />
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'
import RenewButton from '@/app/librarian/circulation/check_in/modals/RenewButton.vue'
import ReturnButton from '@/app/librarian/circulation/check_in/modals/ReturnButton.vue'

const props = defineProps<{ patron: Patron | null }>()

const modal = ref<InstanceType<typeof Modal>>()
const auth = authStore()
const parse = useParser()
const emit = defineEmits(['refresh'])

const loading = ref(false)
const pagination = ref<Pagination>()
const history = ref<Circulation[]>([])

const patronName = computed(() => props.patron?.user?.name?.full ?? 'Patron Borrowing History')
const patronType = computed(() => props.patron?.patron_type?.name ?? 'Unknown patron type')

function open() {
  modal.value?.open()
  search()
}

async function refreshHistory() {
  await search()
  emit('refresh')
}

async function search() {
  if (!props.patron?.id) return

  loading.value = true
  try {
    const res = await get<{ data: Pagination }>('circulation', {
      patron_id: props.patron.id,
      library_id: auth.user?.library?.id,
      per_page: 100,
    })
    pagination.value = res.data
    history.value = [...(res.data?.data ?? [])].sort((a, b) => new Date(b.borrowed_at).getTime() - new Date(a.borrowed_at).getTime())
  } finally {
    loading.value = false
  }
}

defineExpose({ open })
</script>
