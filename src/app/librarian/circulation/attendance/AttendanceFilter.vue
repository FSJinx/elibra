<template>
  <Button icon="filter" variant="restore" @click="modal?.open()">Filter</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Filter Attendance Logs" icon="filter" />
    <ModalBody class="flex flex-col gap-5 p-5">
      <Control direction="col">
        <Label id="attendance-program">Program</Label>
        <Select id="attendance-program" title="Program" v-model="filters.program">
          <Option value="">All program</Option>
        </Select>
      </Control>
      <Control direction="col">
        <Label id="attendance-department">Departments</Label>
        <Select id="attendance-department" title="Departments" v-model="filters.department">
          <Option value="">All departments</Option>
        </Select>
      </Control>

      <Control direction="col">
        <Label id="attendance-from">Date</Label>
        <div class="flex items-center gap-3">
          <Label id="attendance-from">From</Label>
          <DatePicker id="attendance-from" enable-clear v-model="filters.date_from" />
          <Label id="attendance-from">To</Label>
          <DatePicker id="attendance-from" enable-clear v-model="filters.date_to" />
        </div>
      </Control>
    </ModalBody>

    <ModalFooter class="gap-2">
      <Button variant="danger">Cancel</Button>
      <Button variant="success">Apply</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const modal = ref<typeof Modal | null>(null)
const filters = reactive({
  program: '',
  department: '',
  status: '',
  date_from: '',
  date_to: '',
})
</script>

<style scoped></style>
