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

    <ModalFooter class="flex flex-col p-5">
      <div class="flex justify-center" v-if="loading">
        <Spinner />
      </div>

      <div class="flex flex-col" v-else>
        <div class="" v-if="!items?.length">No result found</div>
        <template v-else>
          <p class="font-medium mb-3">Results</p>
          <button v-for="i in items" class="px-5 py-3 text-left rounded-lg cursor-pointer border-transparent border hover:border-primary/50 hover:bg-primary-soft/50 hover:text-primary transition-all duration-200" @click="add(i)">{{ i.title }}</button>
        </template>
      </div>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'
import { usePopup } from '../../../../../composables/ui/usePopup'

const modal = ref<InstanceType<typeof Modal>>()
const item = useItemStore()
const items = ref<Item[] | null>(null)
const params = item.params
const loading = ref<boolean>(false)
const pop = usePopup()
const route = useRoute()

function open() {
  params.query = ''
  items.value = null
  modal.value?.open()
}

async function search() {
  loading.value = true
  const res = await item.fetch(true)

  console.log(res)

  items.value = res?.data?.data
  loading.value = false
}

async function add(i: any) {
  const confirm = await pop.confirm({ text: `Are you sure you want ${i.title}?` })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const acq = await useAcquisitionLinesStore().create({ item_id: i.id, acquisition_id: route.params.id, quantity: 1 })
      pop.success(acq.message)
      close()
    } catch (e: any) {
      pop.error(e.response.message)
      throw e
    }
  }
}
</script>

<style scoped></style>
