<template>
  <Modal ref="createCampus" :has-inputs="hasInputs">
    <template #header>New Campus Form</template>

    <div class="p-5">
      <Form id="campus-form" cols="2" @submit="submitForm">
        <template #body>
          <Control direction="col">
            <Label required id="campus-name">Campus Name</Label>
            <Input id="campus-name" placeholder="e.g. Echague Campus" v-model="campus.name" required enable-clear />
          </Control>
          <Control direction="col">
            <Label required id="campus-code">Campus Code</Label>
            <Input id="campus-code" placeholder="e.g. ISU-E" v-model="campus.code" required enable-clear />
          </Control>
          <Control direction="col" class="col-span-2">
            <Label required id="campus-code">Campus Address</Label>
            <Input id="campus-code" placeholder="e.g. San Fabian, Echague, Isabela" helper="Enter the full address of the campus" v-model="campus.address" required enable-clear />
          </Control>
        </template>
      </Form>
    </div>

    <template #footer>
      <div class="flex items-center justify-end-safe gap-2">
        <Button @click="clearInput">Clear</Button>
        <Button type="submit" variant="primary" form="campus-form">Create</Button>
        <Button variant="danger" @click="(clearInput(), nextTick(() => close()))">Cancel</Button>
      </div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const createCampus = ref<InstanceType<typeof Modal> | null>(null)

const campus = reactive({
  name: '',
  code: '',
  address: '',
})

const open = () => createCampus.value?.open()
const close = () => createCampus.value?.close()

const submitForm = () => {
  console.log(campus)
}

const hasInputs = computed(() => {
  return campus.name.length > 0 || campus.code.length > 0 || campus.address.length > 0
})

const clearInput = () => {
  campus.address = ''
  campus.code = ''
  campus.name = ''
}

defineExpose({ open, close })
</script>

<style scoped></style>
