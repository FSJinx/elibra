<template>
  <Button @click="open()">Add New</Button>

  <Modal ref="subjectModal" enable-close-btn>
    <template #header>Add subjects</template>
    <ModalHeader use-default-layout title="Add Subjects" />

    <Form class="p-5" @submit="addSubject">
      <div class="flex items-center gap-2">
        <Input id="subject" v-model="subject" placeholder="Enter subject name" :warning="error" />
        <Button type="submit" icon="plus" variant="primary" :disabled="!subject.trim()"></Button>
      </div>

      <div class="">
        <h1 class="font-medium my-3">Subjects</h1>

        <div class="flex flex-col gap-2">
          <Button v-for="(item, index) in subjects" :key="index" align="left" @click="removeSubject(item)">
            {{ item }}
            <span class="ml-auto" :data-title="`Remove ${item}`">
              <Icon icon="x-lg" />
            </span>
          </Button>

          <p v-if="model.length === 0" class="text-sm text-foreground-secondary py-2">No subjects added yet.</p>
        </div>
      </div>
    </Form>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const subjectModal = ref<typeof Modal | null>(null)

const model = defineModel<string[]>({ default: () => [] })
const subject = ref('')
const error = ref('')

const subjects = computed(() => {
  return model.value
})

function open() {
  subjectModal.value?.open()
}

function addSubject() {
  const trimmed = subject.value.trim()

  if (!trimmed) return

  if (model.value.includes(trimmed)) {
    error.value = `"${trimmed}" is already added.`
    subject.value = ''
    return
  }

  model.value.push(trimmed)
  console.log('model after push:', model.value) // ← add this
  subject.value = ''
}

async function removeSubject(subject: string) {
  const res = await pop.confirm({ title: 'Delete', text: `Are you sure you want to delete ${subject}?` })

  if (res.isConfirmed) {
    model.value = model.value.filter((item) => item !== subject)
  }
}
</script>

<style scoped></style>
