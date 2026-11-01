<template>
  <Button class="hover:text-info" size="sm" @click="open">Edit</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Edit Loan Status" subtitle="Only the circulation status can be changed here." icon="arrow-repeat" />
    <ModalBody class="p-5">
      <Form id="edit-loan-status-form" class="space-y-4" @submit="submit">
        <Control id="loan-status" col required>
          <Label>Status</Label>
          <Select v-model="status" placeholder="Select loan status">
            <Option v-for="option in statuses" :key="option.value" :value="option.value">{{ option.label }}</Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="edit-loan-status-form" variant="success" type="submit" :loading="saving">
        {{ saving ? 'Saving...' : 'Save Status' }}
      </Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import type Modal from '@/components/my/Modal.vue'

const props = defineProps<{ data: Loan }>()
const emit = defineEmits(['refresh'])
const loan = loanStore()
const pop = usePopup()

const modal = ref<InstanceType<typeof Modal>>()
const status = ref(props.data.status)
const saving = ref(false)
const statuses = [
  { value: 'borrowed', label: 'Borrowed' },
  { value: 'returned', label: 'Returned' },
  { value: 'overdue', label: 'Overdue' },
  { value: 'lost', label: 'Lost' },
  { value: 'cancelled', label: 'Cancelled' },
]

function open() {
  status.value = props.data.status
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  if (status.value === props.data.status) {
    close()
    return
  }

  saving.value = true
  pop.load()
  try {
    const res = await loan.updateStatus(props.data.id, status.value)
    pop.unload()
    close()
    emit('refresh')
    await pop.success(res.message ?? 'Loan status updated successfully.')
  } catch (e: any) {
    pop.unload()
    pop.error(e?.response?.data?.message ?? 'Unable to update the loan status.')
  } finally {
    saving.value = false
  }
}
</script>
