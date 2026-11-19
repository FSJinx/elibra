<template>
  <Button variant="primary" icon="plus-lg" @click="open">New Inventory</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Create Inventory" subtitle="Capture all catalog copies and prepare a shelf-checking PDF" icon="boxes" />

    <ModalBody>
      <Form class="p-5" id="new-inventory" @submit="submit">
        <div class="rounded-xl border border-primary/20 bg-primary/5 p-5">
          <div class="flex items-start gap-3">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary text-white">
              <Icon icon="file-earmark-pdf" />
            </div>
            <div>
              <h3 class="font-semibold">Library-wide inventory</h3>
              <p class="mt-1 text-sm leading-6 text-foreground-secondary">
                This captures every catalog copy in the current library, including its accession number and shelf location. The generated PDF is for manual shelf-to-shelf verification.
              </p>
            </div>
          </div>
        </div>
      </Form>
    </ModalBody>

    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button type="submit" form="new-inventory" variant="primary" left-icon="printer">Create & Download PDF</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const inventory = inventoryStore()

const emit = defineEmits(['refresh'])

const defaultForm = (): StoreInventory => ({})

const modal = ref<InstanceType<typeof Modal>>()
const form = ref<StoreInventory>(defaultForm())

function open() {
  Object.assign(form.value, defaultForm())
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  const confirm = await pop.confirm({ text: 'Create inventory for all catalog copies in this library and download its PDF?' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await inventory.create(form.value)
      const pdf = await inventory.downloadPdf(res.data.id)
      const url = URL.createObjectURL(pdf)
      const link = document.createElement('a')
      link.href = url
      link.download = `${res.data.inventory_code}.pdf`
      link.click()
      URL.revokeObjectURL(url)
      pop.success(res.message ?? 'Inventory created successfully')
      emit('refresh')
      close()
    } catch (e: any) {
      pop.error(e?.response?.data?.message ?? 'Something went wrong, please try again')
    } finally {
      pop.unload()
      close()
    }
  }
}
</script>

<style scoped></style>
