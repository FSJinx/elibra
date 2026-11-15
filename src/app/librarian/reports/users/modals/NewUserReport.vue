<template>
  <Button left-icon="plus-lg" variant="primary" @click="open">New Report</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="New User Report" subtitle="Generate a new users report" icon="people" />
    <ModalBody class="p-5">
      <Form class="flex flex-col gap-5" id="new-report" @submit="submit">
        <Control id="report-role" required>
          <Label>Role</Label>
          <MultiSelect v-model="form.filters.role" placeholder="Select role" title="Roles">
            <MultiOption value="admin" label="Admin" />
            <MultiOption value="librarian" label="Librarian" />
            <MultiOption value="patron" label="Patron" />
          </MultiSelect>
        </Control>

        <Control id="report-format" required>
          <Label>Format</Label>
          <Select v-model="form.format" title="Formats" placeholder="Select report format">
            <Option value="pdf" label="PDF" />
            <Option value="xcs" label="Excel" />
          </Select>
        </Control>

        <Control id="report-date-range" col required>
          <Label>Date Created</Label>

          <div class="flex items-center gap-5" id="">
            <Label>From</Label>
            <DatePicker v-model="form.created_from" :max="form.created_to ?? today" />
            <Label>To</Label>
            <DatePicker v-model="form.created_to" :min="form.created_from" :max="today" />
          </div>
        </Control>

        <Control id="report-columns" col>
          <Label>Columns Included</Label>
          <MultiSelect v-model="form.filters.columns" title="Columns" placeholder="Select columns included">
            <MultiOption value="renewal_count" label="renewal_count" />
            <MultiOption value="fine_charged" label="fine_charged" />
            <MultiOption value="notes" label="notes" />
          </MultiSelect>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="new-report" variant="success" type="submit">Submit</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const report = reportStore()

// Default Values
const defaultForm = (): StoreReport => ({
  created_from: '',
  created_to: '',
  filters: {
    role: [],
    columns: [],
  },
})
const today = ref(new Date().toLocaleDateString('en-CA'))

const modal = ref<InstanceType<typeof Modal>>()
const form = reactive(defaultForm())

function open() {
  Object.assign(form, null)
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  pop.load()
  try {
    const res = await report.store(form)
    pop.success(res.message)
  } catch (e: any) {
    pop.error(e.response?.data?.message)
    throw e
  }
}

watch(form, console.log, { deep: true, immediate: true })
</script>

<style scoped></style>
