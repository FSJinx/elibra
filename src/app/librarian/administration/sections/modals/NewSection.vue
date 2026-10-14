<template>
  <Button variant="primary" @click="open" icon="plus-lg">New Section</Button>

  <Modal ref="modal" size="normal">
    <ModalHeader use-default-layout title="New Section" subtitle="Add new section to this section" icon="building-add" />

    <ModalBody>
      <Form id="create-section" class="flex flex-col gap-2 p-5" @submit="create">
        <Control id="section-name" required col>
          <Label>Name</Label>
          <Input type="text" placeholder="e.g. General Section" v-model="form.name" />
        </Control>
        <Control id="section-library" nullable col>
          <Label>Assigned Librarian</Label>
          <Select v-model="form.librarian_id">
            <Option value="" selected disabled>Select head of this section</Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>

    <ModalFooter class="gap-2">
      <Button form="create-section" type="submit" variant="success">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()
const section = sectionStore()
const pop = usePopup()
const auth = authStore()
const emit = defineEmits(['refresh'])

const form = reactive({
  name: '',
  librarian_id: '',
  library_id: auth.user?.library?.id ?? null,
})

function open() {
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function create() {
  const confirm = await pop.confirm({ text: 'Are you sure to add this section to your library?' })

  if (confirm.isConfirmed) {
    pop.load()

    try {
      const res = await section.create(form)
      close()
      pop.success(res.message ?? 'Added successfully')
      return emit('refresh')
    } catch (e: any) {
      console.log(e.response)

      const message = e.response.data.message
      pop.error(message ?? 'Error')
    }
  }
}
</script>

<style scoped></style>
