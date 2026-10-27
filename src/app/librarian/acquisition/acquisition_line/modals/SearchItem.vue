<template>
  <Button @click="open" data-title="Search item in the catalog for this acquisition">Search Catalog</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Search Catalog" subtitle="Search an item through the catalog and add to this acquisition" icon="search" />

    <ModalBody class="flex flex-col p-5 gap-5">
      <Form id="acquisition-item" class="flex items-center gap-3" @submit="search">
        <Input id="search-catalog" type="text" placeholder="Search item by title or subtitle" v-model="params.query" auto-focus />
        <Button type="submit" variant="primary">Search</Button>
      </Form>

      <p class="font-light" v-if="lastSearch">
        Results for <span class="font-semibold">"{{ lastSearch }}"</span>
      </p>

      <Card class="flex flex-col p-0! divide-y divide-border gap-0!">
        <div class="flex justify-center p-5" v-if="loading">
          <Spinner />
        </div>

        <div class="p-5" v-else-if="!items?.length">{{ lastSearch ? 'No result found' : 'No items here yet, try searching.' }}</div>

        <button v-else v-for="(i, index) in items" class="p-5 text-left cursor-pointer hover:bg-slate-100 transition-all duration-200" @click="add(i)">{{ index + 1 }}. {{ i.title }}</button>
      </Card>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'
import { usePopup } from '../../../../../composables/ui/usePopup'
import { ItemParams } from '@/stores/catalog/item.store'

const modal = ref<InstanceType<typeof Modal>>()
const item = useItemStore()
const items = ref<Item[] | null>(null)
const params = ref<Partial<ItemParams>>({
  query: '',
})
const loading = ref<boolean>(false)
const pop = usePopup()
const route = useRoute()

const lastSearch = ref<string | null>(null)
const emit = defineEmits(['update:Lines'])

async function open() {
  params.value.query = ''
  lastSearch.value = null
  items.value = null
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function search() {
  loading.value = true
  const res = await item.fetch(params.value)
  lastSearch.value = params?.value?.query as string

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
      emit('update:Lines', acq.data)
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
