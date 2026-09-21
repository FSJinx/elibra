<template>
  <Button class="text-danger!" left-icon="trash" @click="open" data-title="View deleted campuses on the record"></Button>

  <Modal ref="modal" :loading="loading">
    <ModalHeader use-default-layout title="Deleted Campuses" subtitle="Manage deleted campuses" icon="buildings" icon-color="danger" />

    <ModalBody>
      <div class="flex flex-col min-h-100 divide-y divide-border">
        <div class="flex-1 flex items-center justify-center" v-if="loading">
          <Spinner />
        </div>
        <div class="flex flex-col justify-center text-center flex-1" v-else-if="data !== null && !data.length">No deleted campus yet.</div>
        <template v-for="d in data" v-else>
          <div class="flex items-center p-5 gap-3 hover:bg-secondary">
            <span class="mr-auto">{{ d.name }} ({{ d.code }})</span>

            <Button size="sm" variant="text" class="text-restore!">Restore</Button>
            <Button size="sm" class="text-danger!" @click="remove(d)" icon="trash" data-title="Delete campus permanently" />
          </div>
        </template>
      </div>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const modal = ref<typeof Modal | null>(null)
const loading = ref<boolean>(false)
const data = ref<Campus[] | null>(null)
const pop = usePopup()

async function fetch() {
  loading.value = true
  try {
    const res = await get('campus/deleted')
    data.value = res.data
  } catch (e: any) {
    throw e
  } finally {
    loading.value = false
  }
}

function open() {
  fetch()
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function remove(d: Campus) {
  const confirm = await pop.confirm({ text: `Are you sure you want to delete ${d.name} permanently? This action is irreversible.` })

  if (confirm.isConfirmed) {
    loading.value = true
    pop.load()
    try {
      const res = await del(`campus/delete-permanent/${d.id}`)
      await fetch()
      pop.success(res.message)
    } finally {
      loading.value = false
    }
  }
}
</script>

<style scoped></style>
