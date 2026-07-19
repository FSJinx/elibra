<template>
  <Modal ref="addNewCampus" position="top" size="large">
    <template #header>
      <div class="flex items-center gap-3 text-primary">
        <span class="p-2 bg-slate-100 border border-slate-300 rounded-lg shrink-0">
          <Icon icon="Building2" />
        </span>
        <div class="flex flex-col justify-center">
          <h4 class="font-semibold text-lg leading-5 mt-1">Add New Campus</h4>
          <p class="text-sm text-slate-500">Create and register a record for a new campus.</p>
        </div>
      </div>
    </template>

    <div class="flex flex-col">
      <eButton icon-left="Archive" class="ml-auto" variant="outline-hover"> Save to Draft</eButton>

      <div class="grid grid-cols-2"></div>
    </div>

    <div class="h-screen"></div>
  </Modal>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import Icon from '@/components/ui/eIcon.vue'
import eButton from '@/components/ui/eButton.vue'
const addNewCampus = ref<InstanceType<typeof Modal> | null>(null)

function open() {
  addNewCampus?.value?.open()
}

function close() {
  addNewCampus?.value?.close()
}

defineExpose({ open, close })
</script>

<style scoped></style>
