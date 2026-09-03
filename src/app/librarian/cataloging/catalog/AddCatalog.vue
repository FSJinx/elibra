<template>
  <div class="size-full flex flex-col overflow-hidden bg-background">
    <div class="w-full flex flex-col border-b border-border bg-background">
      <div class="flex items-center p-3 gap-2">
        <Button icon="arrow-left" variant="text" as="link" :to="{ name: 'librarian.catalog' }"></Button>
        <H1>Add New Item </H1>
      </div>
      <div class="flex items-center px-5">
        <template v-for="link in links">
          <router-link class="p-3 border-b-3 border-transparent transition-all duration-300" :to="{ name: `librarian.catalog.add-new.${link.path}` }" variant="text" exact-active-class="!border-primary text-primary">{{ link.name }}</router-link>
        </template>
      </div>
    </div>

    <div class="w-full overflow-y-auto p-8">
      <H1>New {{ $route.meta.breadcrumb }}</H1>
      <p class="mb-5">Please fill all required fields and re-check your inputs before submitting.</p>
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
const links = [
  { path: 'book', name: 'Book' },
  { path: 'academics', name: 'Academics' },
  { path: 'serials', name: 'Serials' },
]
</script>

<style scoped></style>
