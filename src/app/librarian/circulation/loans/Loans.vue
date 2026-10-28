<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Loans" description="Manage patrons' loans and returns." icon="laptop">
      <div class="flex items-center justify-end gap-2 text-sm">
        <router-link :to="{ name: link.path }" v-for="link in links" class="px-4 py-1 rounded-md border border-transparent transition-all duration-150" exact-active-class="bg-primary-soft/25 text-primary border-primary/50!">
          {{ link.name }}
        </router-link>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
const links = [
  { name: 'Issue', path: 'librarian.circulation.loans' },
  { name: 'Reservations', path: 'librarian.circulation.loans.reservation' },
]
</script>
