<template>
  <div class="relative w-full max-w-375 flex items-start gap-5 mx-auto">
    <Card class="shrink-0 sticky top-0">
      <CardBody class="w-60 border border-border rounded-lg overflow-hidden">
        <img :src="coverImage" alt="Item cover" class="w-full h-auto" />
      </CardBody>
    </Card>

    <Form class="flex-1 flex flex-col gap-3" @submit="submit">
      <!-- Preview Card -->
      <Card class="flex-1 sticky top-0 z-20 shadow-md">
        <div class="flex items-start justify-between gap-5">
          <div class="">
            <p class="text-sm uppercase font-semibold text-muted-foreground">{{ item_type?.name }}</p>
            <Title :level="1">{{ props.data?.title }}</Title>
            <p class="text-muted-foreground text-sm italic">{{ props.data.subtitle ?? 'No additional information or subtitle for this material.' }}</p>
          </div>

          <div class="action-buttons flex justify-end gap-2">
            <Button variant="danger" v-if="editing" @click="cancelEdit">Cancel</Button>
            <Button left-icon="pencil-square" v-else @click="toggleEdit">Edit</Button>
            <Button variant="success" v-if="editing" type="submit" left-icon="save">Save</Button>
          </div>
        </div>
      </Card>

      <!-- BASIC INFORMATION -->
      <BaseSection v-model="form" :editing="editing" />

      <!-- PHYSICAL DESCRIPTION INFORMATION -->
      <PhysicalDescriptionSection v-model="form" :editing="editing" />

      <!-- PHYSICAL DESCRIPTION INFORMATION -->
      <SerialSection v-model="form" :editing="editing" />

      <AuthorSection v-model="form" :editing="editing" />

      <!-- AUTHOR CONTROL INFORMATION -->
      <AuthorityControlSection v-model="form" :editing="editing" />

      <!-- Subject / Keywords -->
      <Card class="" v-if="!editing">
        <CardHeader>
          <Title :level="2" class="text-primary">Subjects / Topic / Keywords</Title>
          <p class="text-sm">The keywords that can help for searching this material</p>
        </CardHeader>

        <CardBody class="flex flex-wrap gap-3">
          <p class="p-5 text-center w-full text-muted-foreground" v-if="!form.keywords?.length">No keywords for this.</p>
          <Chip v-for="tag in props.data.keywords" v-else>{{ tag }}</Chip>
        </CardBody>
      </Card>
    </Form>
  </div>
</template>

<script setup lang="ts">
import AuthorityControlSection from '@/app/librarian/collections/catalog/forms/sections/AuthorityControlSection.vue'
import AuthorSection from '@/app/librarian/collections/catalog/forms/sections/AuthorSection.vue'
import BaseSection from '@/app/librarian/collections/catalog/forms/sections/BaseSection.vue'
import PhysicalDescriptionSection from '@/app/librarian/collections/catalog/forms/sections/PhysicalDescriptionSection.vue'
import SerialSection from '@/app/librarian/collections/catalog/forms/sections/SerialSection.vue'
import book from '@/assets/images/default_book.png'
import { backendRoute } from '@/plugins/axios'

const props = defineProps<{ data: Item }>()
const option = optionStore()
const pop = usePopup()

const item_type = computed(() => option.item_types?.find((a) => a.id === props.data.item_type_id) ?? null)

const editing = ref<boolean>(false)
const form = ref<Item>({} as Item)
const hasChanges = computed(() => JSON.stringify(form.value) !== JSON.stringify(props.data))
const items = useItemStore()
const coverImage = computed(() => {
  const path = props.data.cover_media?.file_path
  return path ? `${backendRoute.replace(/\/api\/?$/, '')}/storage/${path}` : book
})

function toggleEdit() {
  editing.value = true
}

async function cancelEdit() {
  if (hasChanges.value) {
    const confirm = await pop.confirm({ text: 'You have unsaved changes, are you sure you want to cancel editing?' })

    if (!confirm.isConfirmed) {
      return
    }
  }
  defaultForm()
  editing.value = false
}

function defaultForm() {
  const data = structuredClone(toRaw(props.data))
  form.value = {
    ...data,
    authors: (data.authors ?? []).map((author: Author) => ({
      ...author,
      authorship_id: author.authorship_id ?? author.authorship?.authorship_id ?? null,
    })),
    cover_image: null,
  }
}

async function submit() {
  const confirm = await pop.confirm({ text: 'Are you sure you want to update this item?' })

  if (confirm.isConfirmed) {
    try {
      pop.load()
      const response = await items.update(props.data.id, form.value)
      const updated = response.data
      form.value = {
        ...updated,
        authors: (updated.authors ?? []).map((author: Author) => ({
          ...author,
          authorship_id: author.authorship_id ?? author.authorship?.authorship_id ?? null,
        })),
        cover_image: null,
      }
      emit('updated', updated)
      editing.value = false
      pop.success(response.message ?? 'Item updated successfully.')
    } catch (error) {
      throw error
    } finally {
      pop.unload()
    }
  }
}

const emit = defineEmits<{
  updated: [item: Item]
}>()

watch(() => props.data, defaultForm, {
  immediate: true,
})
</script>

<style scoped>
label {
  margin-top: 0.55rem;
}
.control p {
  font-weight: 500;
}
</style>
