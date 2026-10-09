<template>
  <Button @click="open" data-title="Search item in the catalog for this acquisition">Search Catalog</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Search Catalog" subtitle="Search an item through the catalog and add to this acquisition" icon="search" />

    <ModalBody>
      <Form id="acquisition-item" class="flex items-center gap-3 p-5" @submit="search">
        <Input id="search-catalog" type="text" placeholder="Search item by title or subtitle" v-model="params.query" />
        <Button type="submit" variant="primary">Search</Button>
      </Form>
    </ModalBody>

    <ModalFooter class="flex flex-col">
      <div class="p-5" v-if="loading">Nothing here, search now.</div>
      <div class="p-5" v-if="items?.length">Nothing here, search now.</div>
      <template v-else>
        <button v-for="i in items">{{ i.title }}</button>
      </template>
      {{ items }}
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()
const item = useItemStore()
const items = ref<Item[] | null>()
const params = item.params
const loading = ref<boolean>(false)

function open() {
  params.query = ''
  items.value = null
  modal.value?.open()
}

async function search() {
  const res = await item.fetch(true)

  items.value = res
}
</script>

<style scoped></style>
