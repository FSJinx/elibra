<template>
  <div class="size-full flex flex-col">
    <SectionHeader :title="`${selectedLabel} Loans`" description="Manage patrons' loans and returns." icon="laptop">
      <div class="flex items-start justify-end">
        <div class="flex items-center gap-1 p-1.5 bg-tertiary border border-border rounded-md text-sm hover:shadow-md transition-shadow duration-200">
          <router-link :to="{ name: link.path }" v-for="link in links" class="px-4 py-1 rounded-md border border-transparent transition-all duration-150" exact-active-class="bg-primary text-primary-foreground border-primary!">
            {{ link.name }}
          </router-link>
        </div>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
const links = [
  { name: 'Active', path: 'librarian.circulation.loans.active' },
  { name: 'Overdue', path: 'librarian.circulation.loans.overdue' },
  { name: 'History', path: 'librarian.circulation.loans.history' },
]
const selectedLabel = ref('')
const route = useRoute()

watch(
  () => route.name,
  () => (selectedLabel.value = links.find((i) => route.name === i.path)?.name as string),
  { immediate: true },
)
</script>
