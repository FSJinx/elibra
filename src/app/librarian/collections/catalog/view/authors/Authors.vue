<template>
  <div class="size-full flex flex-col gap-3">
    <div class="action-buttons flex justify-end gap-2">
      <!-- <Button variant="primary" left-icon="plus-lg">Add Author</Button> -->
      <AddAuthors />
    </div>

    <Table title="Authors" subtitle="Authors who have contributed to this material" :data-length="authors?.length">
      <Thead>
        <tr>
          <Th class="max-w-50">No</Th>
          <Th class="text-left">Name</Th>
          <Th>Authorship</Th>
          <Th>Actions</Th>
        </tr>
      </Thead>
      <Tbody :data="authors" :loading="loading" cols="4">
        <Tr v-for="(item, index) in authors" :key="index">
          <Td>{{ index + 1 }}</Td>
          <Td class="text-left" :data="parse.name(item).full" />
          <Td :data="authorship(item.authorship?.authorship_id)?.name ?? null" />
          <Td class="space-x-2">
            <Button class="hover:text-info" size="sm">Edit</Button>
            <Button class="hover:text-danger" size="sm">Delete</Button>
          </Td>
        </Tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
import AuthorSection from '@/app/librarian/collections/catalog/forms/sections/AuthorSection.vue'
import AddAuthors from '@/app/librarian/collections/catalog/forms/sections/modals/AddAuthors.vue'

const props = defineProps<{ data: Item }>()

const { fetchResource } = useItemStore()
const options = optionStore()
const parse = useParser()

const authors = ref<Author[] | null>()
// const authors = defineModel<Author>({ default: {} })
const loading = ref<boolean>(false)

const item_type = computed(() => options.item_types?.find((a) => a.id === props.data.item_type_id) ?? null)
const authorship = computed(() => (id: any) => options.authorships?.find((i) => i.id === id))

async function fetchData() {
  loading.value = true
  try {
    const res = await fetchResource('authors', props.data.id)
    authors.value = res.data
  } finally {
    loading.value = false
  }
}
onBeforeMount(fetchData)
</script>

<style scoped></style>
