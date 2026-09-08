<template>
  <Table title="Author" subtitle="Record the authors of this material">
    <template #header>
      <AddAuthor v-model="form.authors" />
    </template>
    <Thead>
      <tr>
        <Th>No.</Th>
        <Th class="text-left">Name</Th>
        <Th class="max-w-75">Authorship</Th>
      </tr>
    </Thead>
    <Tbody :data="form.authors" :loading="false" :cols="3">
      <tr v-for="(item, index) in form.authors" :key="item.id ?? index">
        <Td>{{ index + 1 }}</Td>
        <Td class="text-left">{{ item.first_name }} {{ item.last_name }}</Td>
        <Td>
          <Select :id="`authorship-${item.id ?? index}`" v-model="item.authorship_id" required>
            <Option value="" disabled>Select Authorship</Option>
            <Option v-for="i in authorshipsOption" :key="i.id" :value="i.id">{{ i.name }}</Option>
          </Select>
        </Td>
      </tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
import type { AuthorField } from '@/app/librarian/collections/catalog/forms/form'
import AddAuthor from '@/app/librarian/collections/catalog/modals/AddAuthor.vue'
interface Props {
  errors?: any
  item_type_id: any
}

const { authorships } = authorshipStore()
const props = defineProps<Props>()

const form = defineModel<AuthorField>({ default: {} })
const authorshipsOption = computed(() => authorships?.filter((i) => i.item_type_id === props.item_type_id))
</script>

