<template>
  <Button variant="primary" data-title="Add new acquisition record" @click="modal?.open()" icon="plus-lg">New Acquisition</Button>

  <Modal ref="modal" size="xlarge" :has-inputs="hasInputs">
    <ModalHeader use-default-layout title="Acquisition Form" subtitle="Create new acquisition transaction" icon="building" />
    <Form class="flex flex-col p-5" @submit="submitForm">
      <div class="flex items-center justify-between gap-3">
        <span class="font-light"><span class="font-bold uppercase">directions.</span> Please fill out all required fields that are marked by <span class="text-danger">*</span></span>
        <Button @click="clearForm" :disabled="!hasInputs">Clear Form</Button>
      </div>

      <div class="flex flex-col divide-y divide-border">
        <Control id="acquisition-dealer" required>
          <Label required>Dealer</Label>
          <Input type="text" placeholder="Enter dealer's name" required v-model="form.dealer" auto-focus />
        </Control>
        <Control id="acquisition-mode" required>
          <Label required>Mode of Acquisition</Label>
          <Select v-model="form.acquisition_mode" :error="errors.acquisition_mode?.[0]">
            <Option value="">Select Acquisition Mode</Option>
            <Option value="donation">Donation</Option>
            <Option value="exchange">Exchange</Option>
            <Option value="gift">Gift</Option>
            <Option value="purchase">Purchase</Option>
          </Select>
        </Control>
        <Control id="acquisition-date" required>
          <Label>Date of Acquisition</Label>
          <DatePicker v-model="form.acquisition_date" :max="today" :error="errors.acquisition_date?.[0]" />
        </Control>
        <Control>
          <Label id="acquisition-remarks" class="mb-auto">Remarks</Label>
          <Textarea id="acquisition-remarks" type="text" v-model="form.remarks" placeholder="Enter your remarks here..." />
        </Control>
      </div>

      <div class="flex items-center justify-end">
        <Button type="submit" variant="primary" :disabled="!hasInputs">Submit</Button>
      </div>
    </Form>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'
import { reactive } from 'vue'

const emptyForm = (): Acquisition => ({
  id: null,
  dealer: '',
  acquisition_date: '',
  acquisition_mode: '',
  remarks: '',
})

const pop = usePopup()
const modal = ref<typeof Modal | null>()
const acquisition = useAcquisitionStore()

const errors = reactive<Acquisition>(emptyForm())
const form = reactive<Acquisition>(emptyForm())
const today = new Date().toISOString().split('T')[0]
const hasInputs = computed(() => [form.dealer, form.acquisition_date, form.acquisition_mode, form.remarks].some((value) => String(value ?? '').trim().length > 0))

const clearForm = async () => {
  if (!hasInputs.value) {
    return pop.info("You don't have inputs to clear")
  }

  const result = await usePopup().confirm({
    title: 'Clear Form?',
    text: 'Are you sure you want to clear the inputs in this form? You will lose all your progress, you can save it as draft instead.',
    confirmButtonText: 'Clear Form',
  })

  if (result.isConfirmed) {
    Object.assign(form, emptyForm())
  }
}

async function submitForm() {
  Object.assign(errors, emptyForm())

  const confirm = await pop.confirm({ text: 'Are you sure you want to add this acquisition transaction?' })

  if (confirm.isConfirmed) {
    pop.load()

    try {
      const res = await acquisition.create(form)
      Object.assign(form, emptyForm())
      Object.assign(errors, emptyForm())
      nextTick(() => {
        modal.value?.close()
      })
      pop.success(res.message)
    } catch (e: any) {
      Object.assign(errors, e.response.data.errors ?? {})

      throw e
    }
  }
}
</script>

<style scoped>
.control {
  padding-block: 1.25rem;
}
</style>
