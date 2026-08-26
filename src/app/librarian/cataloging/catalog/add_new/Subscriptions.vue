<template>
  <Form id="subscription-form" cols="2" @submit="submitForm">
    <div class="flex items-center justify-end">
      <Button type="button" left-icon="x-lg" :disabled="!hasInputs" @click="clearForm">Clear Form</Button>
    </div>
    <template #body>
      <Control direction="col">
        <Label required id="subscription-name">Name</Label>
        <Input id="subscription-name" v-model="form.name" placeholder="Enter the subscription name..." required />
      </Control>
      <Control direction="col">
        <Label required id="subscription-link">Link</Label>
        <Input id="subscription-link" v-model="form.link" type="url" placeholder="https://example.com" required />
      </Control>
      <Control direction="col" class="col-span-2">
        <Label id="subscription-description">Description</Label>
        <Input id="subscription-description" v-model="form.description" placeholder="Enter a description..." />
      </Control>
      <Control direction="col">
        <Label id="subscription-thumbnail">Thumbnail ID</Label>
        <Input id="subscription-thumbnail" v-model="form.thumbnail_id" type="number" min="1" placeholder="Enter thumbnail ID..." />
      </Control>
    </template>
    <template #footer>
      <Button type="submit" variant="primary" form="subscription-form">Submit</Button>
    </template>
  </Form>
</template>

<script setup lang="ts">
const form = reactive({
  name: '',
  description: '',
  link: '',
  thumbnail_id: '',
})

const hasInputs = computed(() => Object.values(form).some((value) => String(value).trim().length > 0))

const clearForm = async () => {
  const result = await usePopup().confirm({
    title: 'Clear Form?',
    text: 'Are you sure you want to clear the inputs in this form? You will lose all your progress, you can save it as draft instead.',
    confirmButtonText: 'Clear Form',
  })

  if (result.isConfirmed) {
    Object.assign(form, {
      name: '',
      description: '',
      link: '',
      thumbnail_id: '',
    })
  }
}

const submitForm = () => console.log('Subscription form submitted:', { ...form })
</script>

<style scoped></style>
