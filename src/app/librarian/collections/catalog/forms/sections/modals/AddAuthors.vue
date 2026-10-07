<template>
  <Button @click="open" class="ml-auto hover:text-primary max-w-max">Add Authors</Button>

  <Modal ref="modal" size="large" position="top">
    <ModalHeader use-default-layout title="Add Authors" subtitle="Add authors that contributed into creating this catalog." icon="person-add" />

    <ModalBody class="flex flex-col gap-5 p-5 overflow-hidden!">
      <Form class="flex items-center justify-end gap-3" @submit="search">
        <Input id="search-author" class="w-full max-w-100" type="text" placeholder="Search for author by last name or first name..." required v-model="query" :disabled="loading" />
        <Button type="submit" variant="primary" :loading="loading">Search</Button>
      </Form>

      <div class="flex-1 flex flex-col gap-5 overflow-hidden">
        <div class="" v-if="searchQuery">
          <h3 class="text-lg font-semibold">Showing result for</h3>
          <p class="text-italic">"{{ searchQuery }}"</p>
        </div>

        <Card class="flex-1 flex overflow-y-auto p-0!">
          <div class="flex flex-col w-full divide-y divide-border">
            <div class="m-auto p-10" v-if="loading"><Spinner /></div>
            <p class="p-10 text-center text-muted-foreground" v-else-if="!authors?.length">There's nothing here.</p>
            <template v-for="(a, index) in authors" v-else>
              <button class="flex p-5 hover:bg-secondary cursor-pointer disabled:bg-muted disabled:pointer-events-none" @click="addAuthor(a)" v-if="!selectedAuthors.includes(a)">{{ (index as number) + 1 }}. {{ a.last_name }}, {{ a.first_name }}</button>
            </template>
          </div>
        </Card>
      </div>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import type Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()
const selectedAuthors = defineModel<Author[]>({ default: () => [] })
const query = ref()
const searchQuery = ref()
const loading = ref<boolean>(false)
const authors = computed(() => {
  const selectedIds = new Set(selectedAuthors.value.map((s) => s.id))
  return searchedAuthors.value?.filter((a) => !selectedIds.has(a.id))
})
const searchedAuthors = ref<Author[] | null>()

const pop = usePopup()
const author = useAuthorStore()

function open() {
  query.value = ''
  searchQuery.value = ''
  searchedAuthors.value = null
  modal.value?.open()
}

async function search() {
  console.log('Searched')

  searchedAuthors.value = null
  loading.value = true
  searchQuery.value = query.value
  try {
    const res = await get('authors', { query: query.value })
    searchedAuthors.value = res.data
    return res
  } catch (e: any) {
    throw e
  } finally {
    loading.value = false
    query.value = ''
  }
}

async function addAuthor(a: Author) {
  const confirm = await pop.confirm({ text: `Are you sure you want to add ${a.first_name} to the list?` })

  if (confirm.isConfirmed && !selectedAuthors.value.includes(a)) {
    selectedAuthors.value = [...selectedAuthors.value, a]
  }
}
</script>
