<template>
  <Button variant="default" class="w-75 px-3" left-icon="search" @click="openSearch">
    Search
    <span class="ml-auto shrink-0"><Icon icon="command" /> + K</span>
  </Button>

  <Modal ref="searchModal" position="top" size="normal" @show="() => (searchQuery = '')">
    <Input focus class="border-0" id="system-search" placeholder="Search for system settings..." left-icon="settings" v-model="searchQuery" enable-clear ref="searchInput" />

    <div class="mt-3">
      <h1 class="uppercase font-semibold text-sm">Recent Searches</h1>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import type Input from '@/components/form/Input.vue'
import type Modal from '@/components/my/Modal.vue'

const searchModal = ref<InstanceType<typeof Modal> | null>(null)
const searchInput = ref<InstanceType<typeof Input> | null>(null)
const searchQuery = ref('')

function openSearch() {
  searchModal.value?.open()

  nextTick(() => {
    const el = searchInput.value?.$el?.querySelector('input') || searchInput.value?.$el
    el?.focus?.()
  })
}

function closeModal() {
  searchQuery.value = ''
  searchModal.value?.close()
}

function handleGlobalShortcut(e: KeyboardEvent) {
  if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault()
    openSearch()
  }

  if (e.key === 'Escape') {
    closeModal()
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleGlobalShortcut)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleGlobalShortcut)
})
</script>

<style scoped></style>
