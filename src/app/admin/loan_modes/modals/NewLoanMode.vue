<template>
  <Button variant="primary" left-icon="plus-lg" @click="open">New Loan Mode</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="New Loan Mode" subtitle="Create a new way for patron to loan" icon="cash-coin" />
    <ModalBody class="p-5">
      <Form class="space-y-4" id="create-loan-mode" @submit="submit">
        <Control id="mode-slug" col required>
          <Label>Key</Label>
          <Input type="text" placeholder="e.g. in-house" v-model="form.slug" :error="errors.slug?.[0]" />
        </Control>
        <Control id="mode-name" col required>
          <Label>Name</Label>
          <Input type="text" placeholder="e.g. In House" v-model="form.name" :error="errors.name?.[0]" />
        </Control>
        <Control id="mode-name" col nullable>
          <Label>Description</Label>
          <Input type="text" placeholder="e.g. In House" v-model="form.description" :error="errors.description?.[0]" />
        </Control>

        <Control class="gap-3" col>
          <Checkbox id="mode-renewable" v-model="form.renewable" label="Can Renew" />
          <Checkbox id="mode-charges_fine" v-model="form.charges_fine" label="Charges Fine" />
          <Checkbox id="mode-has_grace_period" v-model="form.has_grace_period" label="Has Grace Period" />
          <Checkbox id="mode-is_active" v-model="form.is_active" label="Active" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="create-loan-mode" variant="success" type="submit">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const loan_mode = loanModeStore()

const emit = defineEmits(['refresh'])
const defaultForm = (): StoreLoanMode => ({
  slug: '',
  name: '',
  description: '',
  renewable: false,
  charges_fine: false,
  has_grace_period: false,
  is_active: true,
})

const modal = ref<InstanceType<typeof Modal>>()
const form = reactive(defaultForm())
const errors = reactive(defaultForm())

function reset() {
  Object.assign(form, defaultForm())
  Object.assign(errors, defaultForm())
}

function open() {
  reset()
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  pop.load()
  Object.assign(errors, defaultForm())
  try {
    const res = await loan_mode.create(form)
    pop.success(res.message ?? 'Loan mode created successfully')
    nextTick()
    emit('refresh')
    close()
  } catch (e: any) {
    Object.assign(errors, e.response?.data?.errors)
    // throw e
  }
}
</script>

<style scoped></style>
