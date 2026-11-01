<template>
  <Button class="hover:text-danger" size="sm" @click="open">Reject</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Reject Submission" :subtitle="`Rejecting manuscript submission titled ${props.data?.title}`" icon="mortarboard" icon-color="danger"/>
    <ModalBody class="p-5">
      <Form id="reject-thesis" @submit="submit">
        <Control id="rejection-remark" col nullable>
          <Label>Remarks</Label>
          <Textarea v-model="form.statusRemark" placeholder="Enter a reason for rejection..." />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button @click="close">Cancel</Button>
      <Button form="reject-thesis" variant="success" type="submit">Reject</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const acarepo = acaRepoStore()

const props = defineProps<{ data: AcaRepo }>()
const emit = defineEmits(['refresh'])

const modal = ref<InstanceType<typeof Modal>>()
const defaultForm = ref(structuredClone(toRaw(props.data)))
const form = ref<UpdateAcaRepo>(defaultForm.value)

function open() {
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  const confirm = await pop.confirm({ text: 'Are you sure you want to reject this submission?' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await acarepo.reject(form.value)
      pop.success(res.message)
      emit('refresh')
      close()
    } catch (e: any) {
      pop.error(e.response?.data?.message ?? 'Something went wrong, please try again later.')
    }
  }
}
</script>

<style scoped></style>
