<template>
  <Button size="sm" variant="info" @click="open">View</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout :title="item.title" :subtitle="`Viewing loanable copies of ${item.title}`" icon="book" />
    <ModalBody class="">
      <Table :data="pagination" title="Copies" :subtitle="`${copies.length} available copy/copies`">
        <template #header>
          <Button class="ml-auto" @click="search">Refresh</Button>
        </template>
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession Number</Th>
            <Th>Action</Th>
          </tr>
        </Thead>
        <Tbody :data="copies" :loading="loading" error-message="No available copies for this item.">
          <Tr v-for="(copy, index) in copies" :key="copy.id">
            <Td :data="index + 1" />
            <Td :data="copy.accession_number" />
            <Td>
              <Button
                v-if="isInBag(copy)"
                size="sm"
                variant="success"
                disabled
                left-icon="check"
              >
                Added
              </Button>
              <Button v-else size="sm" left-icon="bag-plus" @click="add(copy)">
                Add to Bag
              </Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ item: Item }>()
const model = defineModel<Accession[] | null>({ default: () => [] })
const emit = defineEmits(['openBag'])

const accession = accessionStore()
const auth = authStore()
const pop = usePopup()

const modal = ref<InstanceType<typeof Modal>>()
const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const copies = computed(() => accession.data ?? [])

function open() {
  modal.value?.open()
  search()
}

async function search() {
  loading.value = true
  try {
    const res = await accession.fetch({
      item_id: props.item.id,
      loanable: true,
      library_id: auth.user?.library?.id,
    })
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

function isInBag(copy: Accession) {
  return model.value?.some((selected) => selected.id === copy.id) ?? false
}

async function add(copy: Accession) {
  if (isInBag(copy)) return

  const confirm = await pop.confirm({
    text: `Add ${props.item.title} - ${copy.accession_number} to the bag?`,
  })
  if (!confirm.isConfirmed) return

  model.value = [...(model.value ?? []), copy]
  modal.value?.close()
  emit('openBag')
}
</script>

<style scoped></style>
