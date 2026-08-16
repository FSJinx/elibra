<template>
  <div class="p-5 pb-10 space-x-2">
    <h1 class="text-xl font-bold mb-5">Default Select</h1>
    <Form id="select" class="space-y-5">
      <Select id="language" v-model="language" required>
        <Option value="" label="None" disabled />
        <Option value="eng" label="English" />
        <Option value="fil" label="Filipino" />
        <Option value="kor" label="Korean" />
        <Option value="thai" label="Thai" />
      </Select>
      <Button form="select" type="submit">Submit</Button>
    </Form>
  </div>
</template>

<script setup lang="ts">
const sex = ref('')
const language = ref('')

watch(language, (newVal) => {
  console.log(newVal)
})
</script>

<style scoped></style>
