<template>
  <Button size="sm" class="hover:text-info" @click="open">Edit</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Edit Loan Mode" subtitle="Edit loan mode" icon="cash-coin" />
    <ModalBody class="p-5">
      <Form class="space-y-4" id="update-loan-mode" @submit="submit">
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
      <Button form="update-loan-mode" variant="success" type="submit">Save</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'
import { LoanMode } from '@/interfaces/loan_modes'

const pop = usePopup()
const loan_mode = loanModeStore()

const emit = defineEmits(['refresh'])
const props = defineProps<{ data: LoanMode }>()
const defaultForm = ref(structuredClone(toRaw(props.data)))

const modal = ref<InstanceType<typeof Modal>>()
const form = reactive(defaultForm.value)
const errors = ref<Partial<LoanMode>>({
  name: '',
  slug: '',
  description: '',
})

function reset() {
  Object.assign(form, defaultForm.value)
  Object.assign(errors, errors.value)
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
  Object.assign(errors, defaultForm.value)
  try {
    const res = await loan_mode.update(form)
    pop.success(res.message ?? 'Loan mode created successfully')
    emit('refresh')
    close()
  } catch (e: any) {
    Object.assign(errors, e.response?.data?.errors)
  }
}
</script>
