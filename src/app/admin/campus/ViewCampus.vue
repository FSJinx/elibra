<template>
  <div class="size-full flex-1 flex flex-col">
    <div class="flex flex-col border-b border-border bg-background">
      <div class="flex items-center justify-between gap-3 h-20 px-5">
        <Button as="link" :to="{ name: 'admin.campus' }" variant="text" icon="arrow-left">Back to campus list</Button>

        <nav class="flex items-center gap-1 text-sm">
          <router-link v-for="nav in navRoutes" :key="nav.route" :to="{ name: nav.route }" class="py-1.5 px-3 rounded-lg border border-transparent transition-all duration-200" exact-active-class="bg-primary-soft/25 text-primary border-primary/50!">
            {{ nav.name }}
          </router-link>
        </nav>
      </div>
    </div>

    <div class="flex-1 flex flex-col gap-5">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
const campus = useCampusStore()

const route = useRoute()

const navRoutes = [
  { name: 'Overview', route: 'admin.campus.show.overview' },
  { name: 'Branches', route: 'admin.campus.show.branches' },
  { name: 'Departments', route: 'admin.campus.show.departments' },
]

onBeforeUnmount(() => {
  console.log('Unmounting, deleted')

  campus.currentData = null
})
</script>
