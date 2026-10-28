<template>
  <Button class="hover:text-success" size="sm" @click="open">Complete</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Complete Reservation" subtitle="Complete reservation transaction for pickup" icon="bookmark-check" />
    <ModalBody class="p-5">
      <Form id="complete-reservation" @submit="submit">
        <Control id="loan-mode" required col>
          <Label>Loan Mode</Label>
          <Select v-model="loanModeId">
            <Option value="" selected disabled>Select loan mode</Option>
            <Option :value="item.id" v-for="item in options.loan_modes">
              <p>{{ item.name }}</p>
              <p v-if="item.description">{{ item.description }}</p>
            </Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button>Cancel</Button>
      <Button form="complete-reservation" variant="success" type="submit">Submit</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const holding = holdingStore()
const options = optionStore()
const pop = usePopup()

const props = defineProps<{ data: Holding }>()

const modal = ref<InstanceType<typeof Modal>>()
const loanModeId = ref('')

function open() {
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  const confirm = await pop.confirm({ text: 'Proceed checkout?' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await holding.complete({ ...props.data, loan_mode_id: loanModeId.value })
      pop.success(res.message)
      close()
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}
</script>

<style scoped></style>
