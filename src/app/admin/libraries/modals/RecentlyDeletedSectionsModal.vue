<template>
  <Button class="text-danger!" left-icon="trash" @click="open">Recently Deleted</Button>

  <Modal ref="modal" size="large" :loading="loading">
    <ModalHeader use-default-layout title="Deleted Sections" subtitle="Restore deleted sections for this library" icon="diagram-3" icon-color="danger" />
    <ModalBody>
      <div class="flex min-h-60 flex-col divide-y divide-border">
        <div v-if="loading" class="flex flex-1 items-center justify-center">
          <Spinner />
        </div>
        <div v-else-if="sections.deletedData?.length === 0" class="flex flex-1 items-center justify-center text-center">
          No deleted sections for this library.
        </div>
        <div v-for="(section, index) in sections.deletedData ?? []" :key="section.id" class="flex items-center gap-3 p-5 hover:bg-secondary">
          <span class="mr-auto">{{ index + 1 }}. {{ section.name }}</span>
          <Button size="sm" variant="text" class="text-restore!" @click="restore(section)">Restore</Button>
        </div>
      </div>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ libraryId: number }>()
const modal = ref<InstanceType<typeof Modal>>()
const sections = sectionStore()
const pop = usePopup()
const loading = ref(false)

async function open() {
  modal.value?.open()
  loading.value = true
  try {
    await sections.fetchDeleted(props.libraryId)
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to load deleted sections.')
  } finally {
    loading.value = false
  }
}

async function restore(section: Section) {
  const confirmation = await pop.confirm({ text: `Are you sure you want to restore ${section.name}?` })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const res = await sections.restore(section)
    pop.unload()
    await pop.success(res.message ?? 'Section restored successfully')
  } catch (error: any) {
    pop.unload()
    await pop.error(error?.response?.data?.message ?? 'Unable to restore the section.')
  }
}
</script>

<style scoped></style>
