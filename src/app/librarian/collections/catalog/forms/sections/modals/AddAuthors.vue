<template>
  <Button @click="open" class="ml-auto hover:text-primary max-w-max">Add Authors</Button>

  <Modal ref="modal" size="large" position="top">
    <ModalHeader use-default-layout title="Add Authors" subtitle="Add authors that contributed into creating this catalog." icon="person-add" />

    <ModalBody class="flex flex-col gap-3 p-5 overflow-hidden!">
      <div class="flex items-center gap-2">
        <Form class="flex items-center justify-end gap-2 w-full" @submit="search" v-if="!creatingNew">
          <Input id="search-author" class="w-full" type="text" placeholder="Search for author by last name or first name..." required v-model="params.query" :disabled="loading" />
          <Button type="submit" :loading="loading">Search</Button>
        </Form>
        <Button class="ml-auto" :class="[`hover:text-${creatingNew ? 'danger' : 'primary'}`]" :left-icon="creatingNew ? 'x-lg' : 'plus-lg'" @click="toggleCreate">{{ creatingNew ? 'Cancel' : 'Add New' }}</Button>
      </div>

      <Card class="" v-if="creatingNew">
        <CardHeader>New Author Form</CardHeader>
        <CardBody>
          <Form class="flex flex-col gap-3" id="new-author" @submit="submit">
            <p class="uppercase tracking-wide text-foreground-secondary font-semibold text-sm">Author Information</p>
            <Control id="author-last_name" col>
              <Label>Last Name</Label>
              <Input type="text" placeholder="e.g. Dela Cruz" v-model="form.last_name" />
            </Control>
            <Control id="author-first_name" col>
              <Label>First Name</Label>
              <Input type="text" placeholder="e.g. Juan" v-model="form.first_name" />
            </Control>
            <Control id="author-middle_name" col>
              <Label>Middle Initial</Label>
              <Input type="text" placeholder="e.g. A" v-model="form.middle_name" max="2" />
            </Control>
            <Control id="author-suffix" col>
              <Label>Suffix</Label>
              <Select title="Suffix" v-model="form.suffix">
                <Option value="" selected>No suffix</Option>
                <Option value="jr">Jr.</Option>
                <Option value="sr">Sr.</Option>
                <Option value="i">I</Option>
                <Option value="ii">II</Option>
                <Option value="iii">III</Option>
              </Select>
            </Control>
          </Form>
        </CardBody>
        <CardFooter class="flex justify-end">
          <Button form="new-author" variant="success" type="submit">Create</Button>
        </CardFooter>
      </Card>

      <div class="flex-1 flex flex-col gap-5 overflow-hidden" v-else>
        <div class="" v-if="query">
          <h3 class="text-lg font-semibold">Showing result for</h3>
          <p class="text-italic">"{{ query }}"</p>
        </div>

        <Table title="List of Authors" subtitle="Select from the list of authors">
          <Thead>
            <tr>
              <Th>No</Th>
              <Th class="text-left">Name</Th>
              <Th>Actions</Th>
            </tr>
          </Thead>
          <Tbody :loading="loading" :data="authors.data">
            <Tr v-for="(item, index) in authors.data" :key="index">
              <Td :data="pagination?.from + index" />
              <Td class="text-left" :data="item.name?.full" />
              <Td>
                <p v-if="selectedAuthors.some((i) => i.id === item.id)">Selected</p>
                <Button v-else variant="info" size="sm" @click="addAuthor(item)">Select</Button>
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
import type Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const authors = useAuthorStore()

const modal = ref<InstanceType<typeof Modal>>()
const query = ref()
const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = ref<AuthorParams>({
  query: '',
})
const selectedAuthors = defineModel<Author[]>({ default: () => [] })

const defaultForm = (): StoreAuthor => ({
  last_name: '',
  first_name: '',
  middle_name: '',
})
const form = ref<StoreAuthor>(defaultForm())
const creatingNew = ref<boolean>(false)

function open() {
  query.value = ''
  params.value.query = ''
  Object.assign(form, defaultForm())
  creatingNew.value = false
  search()
  modal.value?.open()
}

async function search() {
  loading.value = true
  query.value = params.value?.query
  try {
    const res = await authors.fetch(params.value)
    pagination.value = res.data
  } catch (e: any) {
    throw e
  } finally {
    loading.value = false
    params.value.query = ''
  }
}

async function addAuthor(a: Author) {
  const confirm = await pop.confirm({ text: `Are you sure you want to add ${a.first_name} to the list?` })

  if (confirm.isConfirmed && !selectedAuthors.value.includes(a)) {
    selectedAuthors.value = [...selectedAuthors.value, a]
  }
  modal.value?.close()
}

async function toggleCreate() {
  if (creatingNew.value) {
    creatingNew.value = false
  } else {
    creatingNew.value = true
  }
}

async function submit() {
  const confirm = await pop.confirm({ text: "Create new author and add as this catalog's author?" })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await authors.create(form.value)
      pop.success(res.message)
      addAuthor({ ...res.data, authorship_id: '' })
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}
</script>
