<template>
  <div class="size-full flex flex-col">
    <SectionHeader title="Acquisitions" description="Manage your library's acquisition record" icon="receipt">
      <div class="flex items-center justify-end">
        <div class="flex items-center w-max p-2 bg-slate-200 rounded-lg text-sm">
          <router-link class="py-1 px-2 hover:bg-background rounded-md cursor-pointer font-medium text-muted-foreground" v-for="link in links" :to="{ name: link.path }" exact-active-class=" text-foreground! bg-background border border-border">
            {{ link.name }}
          </router-link>
        </div>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 pt-2">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
const links = ref([
  { name: 'Request', path: 'librarian.collections.acquisition.requests' },
  { name: 'Purchases', path: 'librarian.collections.acquisition.purchases' },
  { name: 'Donations', path: 'librarian.collections.acquisition.donations' },
])
</script>

<style scoped></style>
