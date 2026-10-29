<template>
  <Button class="hover:text-success" size="sm" @click="open">Complete</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Complete Reservation" subtitle="Complete reservation transaction for pickup" icon="bookmark-check" />
    <ModalBody class="p-5">
      <Form id="complete-reservation" @submit="submit">
        <Control id="loan-mode" required col>
          <Label>Loan Mode</Label>
          <Select v-model="loanModeId">
            <Option value="" selected disabled>Select loan mode</Option>
            <Option :value="item.id" v-for="item in options.loan_modes" :key="item.id">
              <p>{{ item.name }}</p>
              <p v-if="item.description">{{ item.description }}</p>
            </Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button @click="close">Cancel</Button>
      <Button form="complete-reservation" variant="success" type="submit" :loading="loading">Complete pickup</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const holding = holdingStore()
const options = optionStore()
const pop = usePopup()

const props = defineProps<{ data: Holding }>()

const modal = ref<InstanceType<typeof Modal>>()
const loanModeId = ref('')
const loading = ref(false)

function open() {
  loanModeId.value = ''
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  if (!loanModeId.value) {
    await pop.info('Select a loan mode to complete this reservation.')
    return
  }

  const confirm = await pop.confirm({ text: 'Proceed checkout?' })

  if (confirm.isConfirmed) {
    loading.value = true
    pop.load()
    try {
      const res = await holding.complete(props.data.id, Number(loanModeId.value))
      pop.unload()
      pop.success(res.message)
      close()
      emit('refresh')
    } catch (e: any) {
      pop.unload()
      const firstValidationError = e?.response?.data?.errors
        ? Object.values(e.response.data.errors).flat()[0]
        : null
      pop.error(firstValidationError ?? e?.response?.data?.message ?? 'Unable to complete this reservation.')
    } finally {
      loading.value = false
    }
  }
}

const emit = defineEmits(['refresh'])

onBeforeMount(async () => {
  try {
    await options.fetch('loan_modes')
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to load loan modes.')
  }
})
</script>

<style scoped></style>
