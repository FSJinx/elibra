<template>
  <Button variant="primary" data-title="Add new item to this purchase" @click="modal?.open()">Add New Item</Button>

  <Modal ref="modal" size="xlarge" :has-inputs="hasInputs" enable-close-btn @show="handleModalShow">
    <ModalHeader use-default-layout title="Add New Item" subtitle="Search the catalog or create a new catalog record" icon="journal-plus" />

    <ModalBody class="flex flex-col">
      <Form class="flex items-end gap-1 p-5 border-b border-border" @submit="searchCatalog">
        <Control class="flex-1" direction="col">
          <Label id="acquisition-item-search">Search catalog</Label>
          <Input id="acquisition-item-search" v-model="search" placeholder="Search by title, author, or ISBN..." left-icon="search" enable-clear auto-focus />
        </Control>
        <Button type="submit" variant="primary" :loading="loading" :disabled="!search.trim()">Search</Button>
        <Button type="submit" variant="primary" :loading="loading" :disabled="!search.trim()">New Item</Button>
      </Form>

      <section class="bg-secondary flex-1">df</section>

      <section v-if="searched && !showCatalogForm" class="flex flex-col gap-3">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="font-semibold">Catalog recommendations</h2>
            <p class="text-sm text-muted-foreground">Select an existing item to add it to this acquisition.</p>
          </div>
          <span class="text-sm text-muted-foreground">{{ recommendations.length }} result{{ recommendations.length === 1 ? '' : 's' }}</span>
        </div>

        <div class="grid gap-2 overflow-y-auto">
          <button v-for="item in recommendations" :key="item.id" type="button" class="flex items-center justify-between gap-4 rounded-lg border border-border p-4 text-left transition hover:border-primary hover:bg-primary/5" @click="selectRecommendation(item)">
            <span class="min-w-0">
              <span class="block truncate font-medium">{{ item.title }}</span>
              <span class="block truncate text-sm text-muted-foreground">{{ item.subtitle || item.author || 'No additional details' }}</span>
            </span>
            <span class="shrink-0 text-sm font-medium text-primary">Select</span>
          </button>
        </div>
      </section>

      <section v-if="showCatalogForm" class="rounded-lg border border-border">
        <div class="border-b border-border p-4">
          <h2 class="font-semibold">Create catalog record</h2>
          <p class="text-sm text-muted-foreground">No matching item was found. Fill out the catalog details to continue.</p>
        </div>
        <Form class="grid gap-3 p-4 md:grid-cols-2" @submit="addCatalogItem">
          <Control>
            <Label id="catalog-title" required>Title</Label>
            <Input id="catalog-title" v-model="catalogForm.title" placeholder="Enter the item title" required />
          </Control>
          <Control>
            <Label id="catalog-author">Author</Label>
            <Input id="catalog-author" v-model="catalogForm.author" placeholder="Enter the author" />
          </Control>
          <Control>
            <Label id="catalog-isbn">ISBN / ISSN</Label>
            <Input id="catalog-isbn" v-model="catalogForm.isbn" placeholder="Enter ISBN or ISSN" />
          </Control>
          <Control>
            <Label id="catalog-publisher">Publisher</Label>
            <Input id="catalog-publisher" v-model="catalogForm.publisher" placeholder="Enter the publisher" />
          </Control>
          <Control class="md:col-span-2">
            <Label id="catalog-notes" class="mb-auto">Notes</Label>
            <Textarea id="catalog-notes" v-model="catalogForm.notes" placeholder="Add notes about this item..." />
          </Control>
          <div class="flex justify-end md:col-span-2">
            <Button type="submit" variant="primary" :disabled="!catalogForm.title.trim()">Continue with catalog item</Button>
          </div>
        </Form>
      </section>

      <section v-if="selectedItem" class="rounded-lg border border-primary/30 bg-primary/5 p-4">
        <p class="text-sm text-muted-foreground">Selected catalog item</p>
        <p class="font-semibold">{{ selectedItem.title }}</p>
      </section>
    </ModalBody>

    <ModalFooter>
      <Button>Done</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

interface CatalogItem {
  id: number
  title: string
  subtitle?: string | null
  author?: string | null
}

interface CatalogForm {
  title: string
  author: string
  isbn: string
  publisher: string
  notes: string
}

const modal = ref<typeof Modal | null>()
const search = ref('')
const loading = ref(false)
const searched = ref(false)
const recommendations = ref<CatalogItem[]>([])
const selectedItem = ref<CatalogItem | null>(null)
const catalogForm = reactive<CatalogForm>({ title: '', author: '', isbn: '', publisher: '', notes: '' })
const showCatalogForm = computed(() => searched.value && !loading.value && recommendations.value.length === 0)
const hasInputs = computed(() => search.value.trim().length > 0 || selectedItem.value !== null || Object.values(catalogForm).some((value) => value.trim().length > 0))

async function searchCatalog() {
  if (!search.value.trim()) return

  loading.value = true
  searched.value = true
  selectedItem.value = null

  try {
    const response = await api.get('item/get', { params: { search: search.value.trim() } })
    recommendations.value = response.data?.data?.data ?? response.data?.data ?? []
    if (recommendations.value.length === 0) {
      catalogForm.title = search.value.trim()
    }
  } catch (error) {
    console.error('Failed to search catalog:', error)
    recommendations.value = []
    catalogForm.title = search.value.trim()
  } finally {
    loading.value = false
  }
}

function selectRecommendation(item: CatalogItem) {
  selectedItem.value = item
}

function addCatalogItem() {
  selectedItem.value = { id: 0, title: catalogForm.title, subtitle: catalogForm.author }
}

function resetForm() {
  search.value = ''
  searched.value = false
  recommendations.value = []
  selectedItem.value = null
  Object.assign(catalogForm, { title: '', author: '', isbn: '', publisher: '', notes: '' })
}

function handleModalShow(opened: boolean) {
  if (opened) resetForm()
}
</script>

<style scoped></style>
