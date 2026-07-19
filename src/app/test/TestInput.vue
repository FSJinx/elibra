<template>
  <div class="p-5 pb-10 space-x-2">
    <h1 class="text-xl font-bold mb-5">Default Text Input</h1>
    <div class="space-y-5">
      <Input id="textInput" v-model="text" label="The Most Default" placeholder="Input here..." />
      <Input id="topInput" v-model="top" label="Top Input" label-position="top" placeholder="Input here..." />
      <Input id="floatInput" v-model="float" label="Float Input" label-position="float" placeholder="Input here..." />
    </div>
  </div>
  <div class="p-5 pb-10 space-x-2">
    <h1 class="text-xl font-bold mb-5">No label Input</h1>
    <div class="space-y-5">
      <Input id="textInput" v-model="text" placeholder="Input here..." />
      <Input id="topInput" v-model="top" label-position="top" placeholder="Input here..." />
      <Input id="floatInput" v-model="float" label-position="float" placeholder="Input here..." />
    </div>
  </div>
  <div class="p-5 pb-10 space-x-2">
    <h1 class="text-xl font-bold mb-5">Cube Text Input</h1>
    <div class="space-y-5">
      <Input id="textInput" v-model="text" label="Default Input" radius="cube" placeholder="Input here..." />
      <Input id="topInput" v-model="top" label="Text Input" radius="cube" label-position="top" placeholder="Input here..." />
      <Input id="floatInput" v-model="float" label="Text Input" radius="cube" label-position="float" placeholder="Input here..." />
    </div>
  </div>
  <div class="p-5 pb-10 space-x-2">
    <h1 class="text-xl font-bold mb-5">Pill Input</h1>
    <div class="space-y-5">
      <Input id="textInput" v-model="text" label="Default Input" radius="pill" placeholder="Input here..." />
      <Input id="topInput" v-model="top" label="Text Input" radius="pill" label-position="top" placeholder="Input here..." />
      <Input id="floatInput" v-model="float" label="Text Input" radius="pill" label-position="float" placeholder="Input here..." />
    </div>
  </div>
  <div class="p-5 pb-10 space-x-2">
    <h1 class="text-xl font-bold mb-5">Required Text Input</h1>
    <div class="space-y-5">
      <Input id="textInput" v-model="text" label="Default Input" placeholder="Input here..." required />
      <Input id="topInput" v-model="top" label="Text Input" label-position="top" placeholder="Input here..." required />
      <Input id="floatInput" v-model="float" label="Text Input" label-position="float" placeholder="Input here..." required />
    </div>
  </div>

  <div class="p-5 pb-10 space-x-2">
    <h1 class="text-xl font-bold mb-5">Different Types Input</h1>
    <div class="space-y-5">
      <Input id="textInput" v-model="text" label="Default" placeholder="Input here..." />
      <Input id="textInput" v-model="text" label="Number" placeholder="Input here..." type="number" />
      <Input id="textInput" v-model="text" label="Email" placeholder="Input here..." type="email" />
      <Input id="textInput" v-model="text" label="Password" placeholder="Input here..." type="password" />
      <Input id="textInput" v-model="text" label="Contact Number" placeholder="Input here..." type="tel" />
    </div>
  </div>
</template>

<script setup lang="ts">
const text = ref<string>('')
const top = ref<string>('')
const float = ref<string>('')

watch(text, (v) => {
  console.log(v)
})
</script>

<style scoped></style>
