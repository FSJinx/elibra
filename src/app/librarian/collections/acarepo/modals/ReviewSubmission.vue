<template>
  <Button class="hover:text-success" size="sm" @click="open">Review</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Review Submission" :subtitle="`Reviewing manuscript submission titled ${props.data?.title}`" icon="mortarboard" />
    <ModalBody class="p-5">
      <p class="uppercase font-semibold tracking-wider text-sm text-muted-foreground mb-5">Reviewing: {{ props.data.title }}</p>

      <div class="flex flex-col gap-3">
        <Control id="submission-title" col>
          <Label>Title</Label>
          <Input v-model="form.title" readonly />
        </Control>
        <Control id="submission-category" col>
          <Label>Category</Label>
          <Input :model-value="categoryLabel" readonly />
        </Control>
        <Control id="submission-language" col>
          <Label>Language</Label>
          <Input :model-value="form.language?.name || form.language_id" readonly />
        </Control>
        <Control id="submission-abstract" col class="sm:col-span-2">
          <Label>Abstract</Label>
          <Textarea v-model="form.description" rows="5" readonly />
        </Control>
        <Control id="submission-library" col>
          <Label>Library</Label>
          <Input :model-value="form.library?.name || form.library_id" readonly />
        </Control>
        <Control id="submission-submitter" col>
          <Label>Submitter</Label>
          <Input :model-value="submitterName" readonly />
        </Control>
        <Control id="submission-program" col>
          <Label>Program</Label>
          <Input :model-value="form.program?.name || form.program_id" readonly />
        </Control>
        <Control id="submission-doi" col class="sm:col-span-2">
          <Label>DOI</Label>
          <Input v-model="form.doi" readonly />
        </Control>
        <Control id="submission-keywords" col class="sm:col-span-2">
          <Label>Keywords</Label>
          <Card class="shadow">
            <CardBody class="flex flex-wrap gap-1">
              <p class="italic text-muted-foreground" v-if="!form.keywords.length">No keywords</p>
              <Chip v-for="item in form.keywords">{{ item }}</Chip>
            </CardBody>
          </Card>
        </Control>
        <Control id="submission-remarks" col class="sm:col-span-2">
          <Label>Status remarks</Label>
          <Textarea v-model="form.statusRemark" rows="3" readonly />
        </Control>
        <Control id="submission-created" col>
          <Label>Submitted</Label>
          <Input :model-value="parse.formatDateAgo(form.created_at) || ''" readonly />
        </Control>
        <Control id="submission-updated" col>
          <Label>Last updated</Label>
          <Input :model-value="parse.formatDateAgo(form.updated_at) || ''" readonly />
        </Control>

        <a class="underline text-sm text-primary" :href="form.doi as string" target="_blank" rel="noopener noreferrer" v-if="form.doi">View Digital Object Identifier (DOI)</a>
        <a class="underline text-sm text-primary" :href="`${storageRoute}/${form.electronic_file}` as string" target="_blank" rel="noopener noreferrer" v-if="form.electronic_file">View submitted PDF</a>
      </div>

      <Form id="approve-thesis" @submit="submit">
        <input v-model="form.statusRemark" type="hidden" />
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button @click="close">Cancel</Button>
      <Button form="approve-thesis" variant="success" type="submit">Approve</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const acarepo = acaRepoStore()
const parse = useParser()

const props = defineProps<{ data: AcaRepo }>()
const emit = defineEmits(['refresh'])

const modal = ref<InstanceType<typeof Modal>>()
const form = ref(structuredClone(toRaw(props.data)))

const categoryLabel = computed(() => {
  const categories: Record<string, string> = { ut: 'Undergraduate thesis', gt: 'Graduate thesis' }
  return categories[props.data.category] ?? (props.data.category || 'Not provided')
})

const submitterName = computed(() => props.data.submitter?.user?.name?.full || props.data.submitted_by || 'Not provided')

function open() {
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  const confirm = await pop.confirm({ text: 'Are you sure you want to approve this submission?' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await acarepo.approve(props.data)
      pop.success(res.message)
      emit('refresh')
      close()
    } catch (e: any) {
      pop.error(e.response?.data?.message ?? 'Something went wrong, please try again later.')
    }
  }
}
</script>

<style scoped></style>
