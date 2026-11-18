<template>
  <Button left-icon="plus-lg" variant="primary" @click="open">New Report</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout :title="`New ${title} Report`" :subtitle="`Generate a library-scoped ${title.toLowerCase()} report`" icon="file-earmark-bar-graph" />
    <ModalBody class="p-5">
      <Form class="flex flex-col gap-5" id="new-generic-report" @submit="submit">
        <Control id="report-format" col required>
          <Label>Format</Label>
          <Select v-model="form.format" title="Formats">
            <Option value="pdf" label="PDF" />
            <Option value="xlsx" label="Excel" />
            <Option value="csv" label="CSV" />
          </Select>
        </Control>

        <Control id="report-date-range" col>
          <Label>Record Date Range</Label>
          <div class="grid gap-3 sm:grid-cols-2">
            <div class="space-y-2">
              <Label>From</Label>
              <DatePicker v-model="form.filters.created_from" :max="form.filters.created_to || today" />
            </div>
            <div class="space-y-2">
              <Label>To</Label>
              <DatePicker v-model="form.filters.created_to" :min="form.filters.created_from" :max="today" />
            </div>
          </div>
        </Control>

        <Control id="report-columns" col>
          <Label>Additional Columns</Label>
          <MultiSelect v-model="form.filters.columns" title="Columns" placeholder="Select additional columns">
            <MultiOption v-for="column in availableColumns" :key="column.value" :value="column.value" :label="column.label" />
          </MultiSelect>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="new-generic-report" variant="success" type="submit" :loading="submitting">Generate</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ type: string; title: string }>()
const emit = defineEmits(['refresh'])

const pop = usePopup()
const report = reportStore()
const modal = ref<InstanceType<typeof Modal>>()
const submitting = ref(false)
const today = new Date().toLocaleDateString('en-CA')

const defaultForm = (): StoreReport => ({
  format: 'pdf',
  filters: {
    columns: [],
    created_from: '',
    created_to: '',
  },
})
const form = reactive(defaultForm())

const columnOptions: Record<string, Array<{ value: string; label: string }>> = {
  attendance: [
    { value: 'status', label: 'Status' },
    { value: 'section', label: 'Section' },
    { value: 'library', label: 'Library' },
  ],
  reservation: [
    { value: 'status', label: 'Status' },
    { value: 'pickup_date', label: 'Pickup date' },
  ],
  item: [
    { value: 'description', label: 'Description' },
    { value: 'keywords', label: 'Keywords' },
  ],
  inventory: [
    { value: 'librarian', label: 'Librarian' },
    { value: 'closed_at', label: 'Closed date' },
  ],
  acquisition: [
    { value: 'remarks', label: 'Remarks' },
    { value: 'dealer', label: 'Dealer' },
  ],
}
const availableColumns = computed(() => columnOptions[props.type] ?? [])

function open() {
  Object.assign(form, defaultForm())
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  submitting.value = true
  pop.load()
  try {
    const res = await report.generate(props.type, form)
    pop.success(res.message ?? 'Report generated successfully')
    emit('refresh')
    close()
  } catch (error: any) {
    pop.error(error?.response?.data?.message ?? 'Unable to generate report')
  } finally {
    submitting.value = false
    pop.unload()
  }
}
</script>
