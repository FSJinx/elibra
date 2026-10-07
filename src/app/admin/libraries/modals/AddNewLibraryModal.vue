<template>
  <Button variant="primary" icon="plus-lg" @click="modal?.open()">New Library</Button>
  <Modal ref="modal" size="large" :has-inputs="hasInputs">
    <ModalHeader use-default-layout title="New Library" subtitle="Add new library to the record" icon="building-fill-add" />

    <ModalBody>
      <Form id="library-form" class="flex flex-col p-5 gap-5" @submit="submit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Library Information</h1>

        <div class="grid grid-cols-2 gap-5">
          <Control col required class="col-span-2">
            <Label id="library-name">Name</Label>
            <Input id="library-name" placeholder="e.g. University Library" v-model="form.name" />
          </Control>
          <Control col>
            <Label id="library-contact">Phone Number</Label>
            <Input id="library-contact" type="tel" pattern="[0-9]{9}" placeholder="e.g. 09XXXXXXXXX" v-model="form.contact_info" />
          </Control>
          <Control col required>
            <Label id="library-email">Email</Label>
            <Input id="library-email" type="email" placeholder="e.g. example@email.com" v-model="form.email" />
          </Control>
          <Control col required class="col-span-2">
            <Label id="library-campus">Campus</Label>
            <Select id="library-campus" title="Campuses" v-model="form.campus_id">
              <Option value="" disabled>Select a campus where this library is located</Option>
              <Option :value="c.id" v-for="c in campus.data">{{ c?.name }} ({{ c?.code }})</Option>
            </Select>
          </Control>
        </div>

        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Library Operation Hours</h1>
        <div class="grid grid-cols-2 gap-5">
          <Control col required>
            <Label id="library-opening_hour">Opening Hours</Label>
            <TimePicker id="library-opening_hour" v-model="form.opening_hour" />
          </Control>
          <Control col required>
            <Label id="library-closing_hour">Closing Hours</Label>
            <TimePicker id="library-closing_hour" v-model="form.closing_hour" />
          </Control>
        </div>
      </Form>
    </ModalBody>

    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close()">Cancel</Button>
      <Button variant="success" type="submit" form="library-form">Create Library</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Form from '@/components/form/Form.vue'
import Modal from '@/components/my/Modal.vue'

const defaultBranch = (): Partial<Library> => ({
  name: '',
  contact_info: '',
  email: '',
  campus_id: '',
  opening_hour: '',
  closing_hour: '',
})

const modal = ref<typeof Modal | null>(null)
const library = useLibraryStore()
const campus = useCampusStore()
const pop = usePopup()
const form = reactive(defaultBranch())
const hasInputs = computed(() => Object.values(form).some((value) => value != null && String(value).length > 0))

async function submit() {
  const confirm = await pop.confirm({ text: `Are you sure you want to add ${form.name} to ${campus.getCampus(form.campus_id)?.name}?` })

  if (confirm.isConfirmed) {
    pop.load()

    try {
      const res = await library.create(form)

      if (res?.status === 'success') {
        Object.assign(form, defaultBranch())
        nextTick(() => modal?.value?.close())
        return pop.success(res?.message)
      }
    } catch (e: any) {
      throw e
    }
  }
}

async function close() {
  if (hasInputs.value) {
    const confirm = await pop.confirm({ text: 'You have unsaved changes, do you really want to cancel adding?' })
    if (!confirm.isConfirmed) return

    Object.assign(form, defaultBranch())
  }

  nextTick(() => modal.value?.close())
}
</script>

<style scoped></style>
