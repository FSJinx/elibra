<template>
  <Button size="sm" @click="open">Edit</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Edit Accession Information" subtitle="Currently Editing" icon="journal" />
    <ModalBody class="p-5">
      <Title :level="5" class="uppercase text-foreground-secondary font-medium! tracking-wide mb-5">Updating Information</Title>

      <Form class="flex flex-col gap-3" id="update-accession" @submit="submit">
        <Control id="accession-number" col>
          <Label>Accession Number</Label>
          <Input type="text" placeholder="Enter this copy's accession number" v-model="form.accession_number" enable-clear />
        </Control>
        <Control id="accession-location" col>
          <Label>Shelf Location</Label>
          <Select v-model="form.section_id">
            <Option value="" selected disabled>Select where this copies will be located</Option>
            <Option :value="item.id" v-for="item in options.sections?.filter((i) => i.library_id === auth.user?.library?.id)">{{ item.name }}</Option>
          </Select>
        </Control>
        <Control id="accession-status" col>
          <Label>Status</Label>
          <Select v-model="form.status">
            <Option value="" disabled>Select status</Option>
            <Option value="available">Available</Option>
            <Option value="reserved">Reserved</Option>
            <Option value="borrowed">Borrowed</Option>
            <Option value="lost">Lost</Option>
            <Option value="missing">Missing</Option>
            <Option value="archived">Archived</Option>
            <Option value="condemned">Condemned</Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="update-accession" variant="success" type="submit">Submit</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ data: Accession }>()
const emit = defineEmits(['refresh'])

const pop = usePopup()
const accession = accessionStore()
const options = optionStore()
const auth = authStore()

const modal = ref<InstanceType<typeof Modal>>()
const suggestedAccessionNumber = ref<string | null>(null)
const defaultForm = (): Accession => structuredClone(toRaw(props.data))
const form = ref<Accession>({ ...defaultForm(), accession_number: suggestedAccessionNumber.value ?? props.data?.accession_number })

async function open() {
  reset()
  pop.load()
  try {
    const res = await accession.getLastAccessionNumber(props.data)
    suggestedAccessionNumber.value = res.data?.accession_number
  } catch (e: any) {
    suggestedAccessionNumber.value = null
  } finally {
    pop.unload()
    modal.value?.open()
  }
}

function close() {
  modal.value?.close()
}

function reset() {
  Object.assign(form, defaultForm())
}

async function submit() {
  const confirm = await pop.confirm({ text: `Confirm edit to this accession` })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await accession.update(form.value)
      ;(pop.success(res.message), reset(), nextTick(), close(), emit('refresh'))
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}
</script>

<style scoped></style>
