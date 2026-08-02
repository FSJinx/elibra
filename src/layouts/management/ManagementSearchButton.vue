<template>
  <Button variant="default" class="justify-between w-75" left-icon="search" @click="searchModal?.open()">
    Search
    <p class="ml-auto shrink-0"><Icon icon="component" /> + K</p>
  </Button>

  <Modal ref="searchModal" position="top" size="normal" :has-inputs="searchQuery.length > 0">
    <!-- <h1 class="flex items-center gap-2 mb-1 tracking-tight"><Icon icon="settings" /> System Settings</h1> -->
    <div class="flex items-center gap-2">
      <Input class="border-0" id="system-search" placeholder="Search for system settings..." left-icon="settings" v-model="searchQuery" enable-clear ref="searchInput" />
      <Button class="border border-border" data-title="Clear and Close" @click="clearClose"><Icon icon="arrow-left" /></Button>
    </div>

    <div class="mt-2 p-1">
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

  // Hintayin ang modal transition/render bago mag-focus
  nextTick(() => {
    // Tinitiyak na gagana ang focus sa custom component ref
    const el = searchInput.value?.$el?.querySelector('input') || searchInput.value?.$el
    el?.focus?.()
  })
}

function clearClose() {
  searchQuery.value = ''
  nextTick(() => {
    searchModal.value?.close()
  })
}

function handleGlobalShortcut(e: KeyboardEvent) {
  if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault()
    openSearch()
  }

  if (e.key === 'Escape') {
    searchModal.value?.close()
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
