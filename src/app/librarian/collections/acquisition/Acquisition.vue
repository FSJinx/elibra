<template>
  <div class="size-full flex">
    <div class="flex-1 flex flex-col max-w-75 bg-background border-r border-border">
        <div class="py-5 px-6 hover:bg-primary cursor-pointer" v-for="link in links">
            {{ link.name }}
        </div>

    </div>
    <SectionHeader title="Acquisition" description="Manage your library's acquisition record" icon="receipt"></SectionHeader>

    <div class="flex-1 flex flex-col"></div>
  </div>
</template>

<script setup lang="ts">
const links = ref([
  { name: 'Request', path: '' },
  { name: 'Purchases', path: '' },
  { name: 'Donations', path: '' },
])
</script>

<style scoped></style>
