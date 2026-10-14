<template>
  <Button @click="open" class="hover:text-primary">Add Keywords</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Keywords / Subjects" subtitle="Add keywords or subjects for this catalog. This will be helpful in OPAC searches." icon="" />

    <ModalBody class="p-5 overflow-hidden!">
      <Form class="flex items-center gap-2 w-125 ml-auto" @submit="add">
        <Input id="item-keyword" type="text" placeholder="Enter keywords or subjects here..." v-model="word" required />
        <Button type="submit" variant="primary" icon="plus-lg">Add</Button>
        <Button class="hover:text-danger" v-if="form.keywords?.length" @click="clear">Clear</Button>
      </Form>

      <h4 class="uppercase tracking-wide font-semibold text-muted-foreground mb-3">Keywords</h4>

      <Card class="overflow-hidden">
        <CardBody class="flex flex-col overflow-y-auto">
          <p class="text-muted-foreground text-center p-5 w-full" v-if="!form.keywords?.length">No keywords yet.</p>
          <div class="flex flex-wrap gap-2" v-else>
            <template v-for="keyword in form.keywords">
              <Chip removable @remove="remove(keyword)">{{ keyword }}</Chip>
            </template>
          </div>
        </CardBody>
      </Card>
    </ModalBody>

    <ModalFooter class="gap-2">
      <Button @click="close" variant="danger">Close</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import type { Form } from '@/app/librarian/collections/catalog/forms/form'
import Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()
const form = defineModel<Partial<Form>>({ default: {} })
const word = ref('')
const pop = usePopup()

function open() {
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

function add() {
  const keyword = word.value.trim()
  if (!keyword) return

  form.value.keywords ??= []

  const exists = form.value.keywords.some((k) => k.toLowerCase() === keyword.toLowerCase())

  if (exists) {
    pop.error(`"${keyword}" is already in the record, try a different one.`)
    return
  }

  form.value.keywords.push(keyword)
  word.value = ''
}

async function remove(text: string) {
  const confirm = await pop.confirm({ text: `Are you sure you want to remove "${text}"?` })

  if (confirm.isConfirmed) {
    form.value.keywords = form.value.keywords?.filter((i) => i !== text)
  }
}

async function clear() {
  const confirm = await pop.confirm({ text: `Are you sure you want to remove all keywords? This is irreversible.` })

  if (confirm.isConfirmed) {
    form.value.keywords = []
    nextTick(() => pop.success('Keywords deleted successfully'))
  }
}
</script>

<style scoped></style>
