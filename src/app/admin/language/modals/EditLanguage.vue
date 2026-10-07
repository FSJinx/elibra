<template>
  <Button size="sm" class="hover:text-warning" @click="modal?.open()">Edit</Button>

  <Modal ref="modal" size="large" :has-inputs="hasChanges">
    <ModalHeader use-default-layout title="Update Language" :subtitle="`Currently editing ${data.name}`" icon="translate" />
    <ModalBody>
      <Form id="new-language" class="flex flex-col gap-3 p-5" @submit="handleSubmit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Language Information</h1>

        <div class="grid grid-cols-3 gap-3">
          <Control id="language-name" col required class="col-span-2">
            <Label>Name</Label>
            <Input type="text" placeholder="e.g. English" v-model="model.name" />
          </Control>
          <Control id="language-code" col required>
            <Label>Code</Label>
            <Input type="text" placeholder="e.g. eng" v-model="model.code" />
          </Control>
        </div>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close()">Cancel</Button>
      <Button type="submit" variant="info" form="new-language" v-if="hasChanges">Update</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{
  data: Language
  languages: any
}>()

const pop = usePopup()
// const languages = useLanguagesStore()
const modal = ref<InstanceType<typeof Modal>>()
const hasChanges = computed(() => JSON.stringify(model) !== JSON.stringify(props.data))

const defaultValue = (): Partial<Language> => ({ ...props.data })

const model = reactive<Partial<Language>>(defaultValue())

async function handleSubmit() {
  const confirm = await pop.confirm({ text: `Are you sure you want to add ${model.name} in the list?` })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await props.languages.update(model)
      pop.success(res.message ?? 'Language updated successfully')
      Object.assign(model, defaultValue())
      nextTick(() => close())
    } catch (e: any) {
      pop.unload()
      throw e
    }
  }
}

async function close() {
  if (hasChanges.value) {
    const confirm = await pop.confirm({ text: 'You have unsaved changes, closing this will delete your progress. Are you sure you want to close this?' })

    if (!confirm.isConfirmed) {
      return
    }
    Object.assign(model, defaultValue())
  }
  nextTick(() => modal.value?.close())
}
</script>

<style scoped></style>
