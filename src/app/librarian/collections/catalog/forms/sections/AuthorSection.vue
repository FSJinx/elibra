<template>
  <Table title="Author" subtitle="Record the authors and authorship of this material. If authorship is blank, check if you've selected an item type." v-if="form.item_type_id">
    <template #header>
      <AddAuthors v-model="form.authors" />
    </template>
    <Thead>
      <tr>
        <Th>No.</Th>
        <Th class="text-left">Name</Th>
        <Th class="max-w-75">Authorship</Th>
        <Th class="">Actions</Th>
      </tr>
    </Thead>
    <Tbody :data="form.authors" :loading="false" :cols="4">
      <tr v-for="(item, index) in form.authors" :key="item.id ?? index">
        <Td>{{ index + 1 }}</Td>
        <Td class="text-left">{{ item.first_name }} {{ item.last_name }}</Td>
        <Td>
          <Select :id="`authorship-${item.id ?? index}`" v-model="item.authorship_id" required>
            <Option value="" disabled>Select Authorship</Option>
            <Option v-for="i in authorships.byItemType(form.item_type_id)" :key="i.id" :value="i.id">{{ i.name }}</Option>
          </Select>
        </Td>
        <Td><Button class="text-danger!" icon="trash" @click="removeAuthor(item)" /></Td>
      </tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
import { getError, type Form } from '@/app/librarian/collections/catalog/forms/form'
import AddAuthors from '@/app/librarian/collections/catalog/forms/sections/modals/AddAuthors.vue'

defineProps<{
  errors: any
}>()

const form = defineModel<Partial<Form>>({ default: {} })
const authorships = authorshipStore()
const pop = usePopup()

async function removeAuthor(a: Author) {
  const confirm = await pop.confirm({ text: `Are you sure you want to remove ${a.first_name} as one of your authors?` })

  if (confirm.isConfirmed) {
    form.value.authors = form.value.authors?.filter((i) => i.id !== a.id)
  }
}

watch(
  () => form.value.item_type_id,
  () => {
    form.value?.authors?.forEach((i) => (i.authorship_id = null))
  },
)
</script>
