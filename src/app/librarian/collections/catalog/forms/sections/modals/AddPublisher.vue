<template>
  <Button @click="open" :disabled="disabled">{{ form?.id ? 'Change' : 'Add Publisher' }}</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Add Publisher" subtitle="Search and add publisher for this catalog. Click add new if publisher is not yet on the database." />

    <ModalBody class="flex flex-col gap-5 p-5 overflow-hidden">
      <Form class="flex items-center gap-2" id="search-publisher" @submit="search">
        <Input type="text" placeholder="Search by publisher's name" v-model="params.query" auto-focus />
        <Button>Search</Button>
      </Form>

      <p v-if="lastQuery">
        Search result for "<span class="font-medium">{{ lastQuery }}"</span>
      </p>

      <div class="flex-1 flex overflow-y-auto">
        <Table title="Publishers" subtitle="Select a publisher from the list below" :data="pagination" @page="params.page = $event" @per_page="params.per_page = $event">
          <Thead>
            <tr>
              <Th>No</Th>
              <Th>Name</Th>
              <Th>Action</Th>
            </tr>
          </Thead>
          <Tbody :data="publisher.data" :loading="loading">
            <Tr v-for="(item, index) in publisher.data" :key="index">
              <Td :data="pagination?.from + index" />
              <Td class="text-left" :data="item.name" />
              <Td>
                <p v-if="form?.id === item.id">Selected</p>
                <Button v-else size="sm" variant="info" @click="select(item)">Select</Button>
              </Td>
            </Tr>
          </Tbody>
          <template #footer />
        </Table>
      </div>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = withDefaults(defineProps<{ disabled?: boolean }>(), { disabled: false })
const form = defineModel<Publisher>({ default: {} })

const publisher = publisherStore()
const pop = usePopup()

const modal = ref<InstanceType<typeof Modal>>()
const lastQuery = ref()
const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const params = ref<PublisherParams>({
  query: '',
})

function open() {
  search()
  nextTick()
  params.value.query = ''
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function search() {
  lastQuery.value = params.value?.query ?? null
  loading.value = true
  try {
    const res = await publisher.fetch(params.value)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

async function select(pub: Publisher) {
  console.log('1. clicked', pub)
  const confirm = await pop.confirm({ text: `Add ${pub.name} as this catalog's publisher?` })
  console.log('2. confirm result', confirm)

  if (confirm.isConfirmed) {
    form.value = pub
    console.log('3. set', form.value)
    close()
  }
}

watch(params, search, { deep: true })
</script>

<style scoped></style>
