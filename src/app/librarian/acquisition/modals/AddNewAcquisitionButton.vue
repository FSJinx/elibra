<template>
  <Button variant="primary" data-title="Add new acquisition record" @click="modal?.open()">Add New</Button>

  <Modal ref="modal" size="large" :has-inputs="hasInputs" enable-close-btn>
    <template #header> New Acquisition Transaction </template>
    <Form class="flex flex-col p-5">
      <div class="flex items-end justify-between gap-3">
        <div class="">
          <h1 class="font-semibold text-lg">Acquisition Form</h1>
          <p class="text-muted-foreground">Please fill out all required fields marked with <span class="text-danger">*</span></p>
        </div>
        <Button @click="clearForm" :disabled="!hasInputs">Clear Form</Button>
      </div>

      <div class="flex flex-col divide-y divide-border">
        <Control>
          <Label id="acquisition-dealer" required>Dealer</Label>
          <Input id="acquisition-dealer" type="text" placeholder="Enter dealer's name" required v-model="form.dealer" />
        </Control>
        <Control>
          <Label id="acquisition-dealer" required>Mode of Acquisition</Label>
          <Select id="acquisition-dealer" v-model="form.acquisition_mode">
            <Option value="">Select Acquisition Mode</Option>
            <Option value="gift">Gift</Option>
            <Option value="purchased">Purchased</Option>
            <Option value="donated">Donated</Option>
          </Select>
        </Control>
        <Control>
          <Label id="acquisition-date" required>Date of Acquisition</Label>
          <DatePicker id="acquisition-date" v-model="form.acquisition_date" :max="today" />
        </Control>
        <Control>
          <Label id="acquisition-remarks" class="mb-auto" required>Remarks</Label>
          <Textarea id="acquisition-remarks" type="text" placeholder="" required v-model="form.remarks" />
        </Control>
      </div>

      <div class="flex items-center justify-end">
        <Button variant="primary" :disabled="!hasInputs">Submit</Button>
      </div>
    </Form>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'
import { Acquisition } from '@/stores/acquisitionStore'
import { popScopeId } from 'vue'

const modal = ref<typeof Modal | null>()
const loading = ref<boolean>(false)
const pop = usePopup()

const emptyForm = (): Acquisition => ({
  id: null,
  dealer: '',
  acquisition_date: '',
  acquisition_mode: '',
  remarks: '',
})

const form = reactive<Acquisition>(emptyForm())
const today = new Date().toLocaleDateString('en-CA')
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

watch(
  form,
  () => {
    console.log(form)
  },
  { deep: true, immediate: true },
)
</script>

<style scoped>
.control {
  padding-block: 1.25rem;
}
</style>
