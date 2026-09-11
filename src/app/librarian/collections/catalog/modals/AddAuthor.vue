<template>
  <Button class="ml-auto" variant="primary" @click="open()">Add New</Button>

  <Modal ref="authorModal" size="large" enable-close-btn>
    <ModalHeader use-default-layout title="Add Author" icon="person-check" />

    <div class="flex flex-col p-5 gap-5">
      <!-- Add Author by Create -->
      <Form v-if="addingAuthor" @submit="createNewAuthor">
        <div class="flex items-center mb-5">
          <span>
            <h1 class="font-medium text-xl">Create New Author</h1>
            <p class="text-sm text-muted-foreground">Please fill up required fields.</p>
          </span>
        </div>
        <template #body>
          <Control direction="col">
            <Label id="author-first_name">First Name</Label>
            <Input id="author-first_name" v-model="newAuthor.first_name" placeholder="Juan" :warning="error" />
          </Control>
          <Control direction="col">
            <Label id="author-middle_initial">Middle Initial</Label>
            <Input id="author-middle_initial" v-model="newAuthor.middle_name" placeholder="A" :warning="error" :max="3" />
          </Control>
          <Control direction="col">
            <Label id="author-last_name">Last Name</Label>
            <Input id="author-last_name" v-model="newAuthor.last_name" placeholder="Dela Cruz" :warning="error" />
          </Control>
        </template>
        <template #footer>
          <Button class="ml-auto" variant="danger" @click="cancelCreate()">Cancel</Button>
          <Button type="submit" variant="success">Create</Button>
        </template>
      </Form>

      <!-- Add author by Search -->
      <div class="flex flex-col gap-5" v-else>
        <Form @submit="searchAuthor">
          <div class="flex items-center gap-3">
            <Input id="" type="text" placeholder="Search authors..." v-model="author" enable-clear />
            <Button variant="info">Search</Button>
            <Button variant="primary" icon="plus" @click="openCreate">Add New</Button>
          </div>
        </Form>

        <div class="flex flex-col border border-border rounded-lg" v-if="author?.trim().length > 0">
          <h1 class="font-semibold p-5 border-b border-border">Search Result</h1>

          <div class="flex flex-col divide-y divide-border">
            <div class="mx-auto p-5" v-if="loadingSearch">
              <Spinner />
            </div>
            <p class="text-muted-foreground text-center p-5" v-else-if="!authors?.length">No matching author, <span class="text-primary" @click="openCreate">add now</span>.</p>
            <template v-for="a in authors" v-else>
              <Button class="disabled:text-muted-foreground" align="left" variant="text" @click="addAuthor(a)" :disabled="isAuthorSelected(a)" :data-title="isAuthorSelected(a) ? 'This author is already added' : ''">{{ a.first_name }} {{ a.last_name }}</Button>
            </template>
          </div>
        </div>

        <!-- List of Added Authors -->
        <div class="" v-else>
          <h1 class="font-medium my-3">Authors</h1>

          <div class="flex flex-col gap-2">
            <Button v-for="(item, index) in model" :key="index" align="left">
              {{ item.first_name }} {{ item.last_name }}
              <span class="ml-auto" :data-title="`Remove ${item}`">
                <Icon icon="x-lg" />
              </span>
            </Button>

            <p v-if="model.length === 0" class="text-sm text-foreground-secondary py-2">No authors added yet.</p>
          </div>
        </div>
      </div>
    </div>
    <template #footer>
      <div class="flex items-center">
        <Button class="ml-auto" @click="close">Done</Button>
      </div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()

const authorModal = ref<typeof Modal | null>(null)
const error = ref('')
const model = defineModel<Author[]>({ default: () => [] })

// Create New Author
const addingAuthor = ref<boolean>(false)
const newAuthor = reactive<Author>({
  last_name: '',
  first_name: '',
  middle_name: '',
})
function openCreate() {
  Object.assign(newAuthor, {
    last_name: 'Dela Cruz',
    first_name: 'Jairus',
    middle_name: 'A',
  })
  addingAuthor.value = true
}
function cancelCreate() {
  addingAuthor.value = false
}
async function createNewAuthor() {
  const res = await pop.confirm({ text: 'Are you sure the inputs are correct?' })

  if (res.isConfirmed) {
    pop.load()
    await api
      .post('/authors', { ...newAuthor })
      .then((res) => {
        addAuthor(res.data?.data)
        console.log(model.value)

        cancelCreate()
      })
      .catch((e) => {
        console.log(e)

        pop.error(e.res?.message)
      })
  }
}

// Add Author
const author = ref<any>()
const authors = ref<Author[]>([])
const loadingSearch = ref<boolean>(false)

async function addAuthor(a: any) {
  if (isAuthorSelected(a)) return

  const res = await pop.confirm({ text: `Add ${a.first_name}${a.middle_name ? ' ' + a.middle_name + '. ' : ' '}${a.last_name} as author?` })
  if (res.isConfirmed) {
    model.value.push({ ...a, authorship_id: '' })

    addingAuthor.value = false
    author.value = ''
  }
}

function isAuthorSelected(author: Author) {
  return model.value.some((selectedAuthor) => {
    if (author.id != null && selectedAuthor.id != null) {
      return String(selectedAuthor.id) === String(author.id)
    }

    return getAuthorName(selectedAuthor) === getAuthorName(author)
  })
}

function getAuthorName(author: Author) {
  return [author.first_name, author.middle_name, author.last_name]
    .filter(Boolean)
    .map((part) => part.trim().toLowerCase())
    .join(' ')
}

async function searchAuthor() {
  if (!author.value.trim()) return

  loadingSearch.value = true
  await api
    .post('/authors/show', { query: author.value.trim() })
    .then((res) => {
      authors.value = res.data?.data
    })
    .finally(() => {
      loadingSearch.value = false
    })
}

function open() {
  author.value = ''
  addingAuthor.value = false
  authorModal.value?.open()
}

function close() {
  authorModal.value?.close()
}

watchDebounced(
  author,
  () => {
    authors.value = []
    searchAuthor()
  },
  { debounce: 500 },
)
</script>

<style scoped></style>
