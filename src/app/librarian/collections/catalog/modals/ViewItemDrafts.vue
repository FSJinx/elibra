<template>
  <Button left-icon="archive" @click="open()">Drafts</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Drafts" subtitle="Your cataloging progress is saved here, click on one and continue cataloging" icon="archive" />
    <ModalBody class="min-h-75">
      <div class="text-center place-content-center h-50 text-muted-foreground" v-if="!data">You have no drafts at the moment.</div>

      <div class="flex flex-col divide-y divide-border overflow-y-auto" v-else>
        <template v-for="(item, index) in data">
          <button class="flex items-center gap-3 px-5 py-3 text-left cursor-pointer hover:bg-secondary" @click="openDraft(item)">
            <div class="">
              <p>
                <span>{{ index + 1 }}. </span>
                <span>{{ item.data?.title ?? 'Untitled Draft' }} </span>
              </p>
              <p class="text-sm text-muted-foreground">{{ item.data.description ?? 'No description for this draft' }}df</p>
            </div>

            <p class="ml-auto text-muted-foreground text-sm">{{ parse.dateTimeAgo(item.created_at) }}</p>
          </button>
        </template>
      </div>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const drafts = draftStore()
const parse = useParser()
const modal = ref<InstanceType<typeof Modal>>()

const data = computed(() => drafts.data.filter((i) => i.key === 'item'))

function open() {
  modal.value?.open()
}

function openDraft(item: any) {
  return router.push({ name: 'librarian.collections.catalog.new', query: { draft_id: item.id } })
}
</script>

<style scoped></style>
