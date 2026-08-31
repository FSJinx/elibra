<template>
  <!-- Search Trigger -->
  <Button variant="default" class="w-75 px-3 text-foreground-secondary hover:text-foreground" left-icon="search" @click="openSearch">
    <span>Search</span>

    <span class="ml-auto flex items-center gap-1 text-xs font-medium text-foreground-secondary">
      <Kbd>Ctrl</Kbd>

      <span>+</span>

      <Kbd>K</Kbd>
    </span>
  </Button>

  <!-- Search Modal -->
  <Modal ref="searchModal" position="top" size="normal">
    <!-- Search Input -->
    <div class="border-b border-border/70 px-4">
      <Input id="system-search" ref="searchInput" v-model="searchQuery" focus enable-clear left-icon="search" placeholder="Search anything..." class="w-full border-0 bg-transparent px-1 py-4 text-base focus:ring-0" @keydown.esc="closeModal" />
    </div>

    <!-- Search Content -->
    <div class="min-h-75 p-3">
      <!-- Empty Query -->
      <template v-if="!searchQuery">
        <div class="px-3 pb-2 pt-1">
          <h2 class="text-[11px] font-semibold uppercase tracking-widest text-foreground-secondary">Recent</h2>
        </div>

        <div class="flex flex-col gap-0.5">
          <Button variant="text" align="left" left-icon="clock" class="searchItem"> Campus Management </Button>

          <Button variant="text" align="left" left-icon="clock" class="searchItem"> User Permissions </Button>

          <Button variant="text" align="left" left-icon="clock" class="searchItem"> Acquisition Requests </Button>
        </div>
      </template>

      <!-- Search Results -->
      <template v-else>
        <div class="px-3 pb-2 pt-1">
          <h2 class="text-[11px] font-semibold uppercase tracking-widest text-foreground-secondary">Results</h2>
        </div>

        <!-- Results go here -->
        <div class="flex min-h-55 flex-col items-center justify-center px-5 text-center">
          <span class="mb-3 flex size-10 items-center justify-center rounded-full bg-muted text-foreground-secondary">
            <Icon icon="search" />
          </span>

          <p class="text-sm font-medium text-foreground">No results found</p>

          <p class="mt-1 max-w-65 text-xs text-foreground-secondary">Try searching for a page, user, campus, or system feature.</p>
        </div>
      </template>
    </div>

    <!-- Keyboard Hints -->
    <div class="flex items-center gap-4 border-t border-border/70 px-4 py-2.5 text-xs text-muted-foreground">
      <span class="flex items-center gap-1.5">
        <Kbd>↑</Kbd>
        <Kbd>↓</Kbd>
        <span>Navigate</span>
      </span>

      <span class="flex items-center gap-1.5">
        <Kbd>↵</Kbd>
        <span>Open</span>
      </span>

      <span class="ml-auto flex items-center gap-1.5">
        <Kbd>Esc</Kbd>
        <span>Close</span>
      </span>
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
    return
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

<style scoped>
.searchItem {
  width: 100%;
  padding-inline: 0.75rem;
}
</style>
