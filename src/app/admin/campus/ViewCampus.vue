<template>
  <div class="flex-1 flex flex-col gap-3 bg-background">
    <div class="flex flex-col border-b border-border">
      <div class="flex items-center gap-3 p-5">
        <Button as="link" :to="{ name: 'admin.campus' }" variant="text" icon="arrow-left">Back</Button>

        <div class="flex items-center ml-auto gap-2">
          <Button left-icon="pencil">Edit Campus</Button>
          <Button icon="three-dots-vertical"></Button>
        </div>
      </div>

      <nav class="flex px-3">
        <router-link v-for="nav in navRoutes" :key="nav.route" :to="{ name: nav.route, params: { id: route.params.id } }" class="p-3" exact-active-class="border-b-2 border-primary text-primary">
          {{ nav.name }}
        </router-link>
      </nav>
    </div>

    <div class="flex-1">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
const loading = ref<boolean>(false)
const campus = ref<Campus | null>(null)
const { setBreadcrumb } = useBreadcrumb()

const route = useRoute()

const navRoutes = [
  { name: 'Overview', route: 'admin.campus.show.overview' },
  { name: 'Departments & Programs', route: 'admin.campus.show.departments' },
  { name: 'Branches', route: 'admin.campus.show.branches' },
]

async function fetchCampusDetails() {
  loading.value = true
  setBreadcrumb('admin.campus.show', null)
  try {
    const res = await api.get(`campus/get/${route.params?.id}`)
    campus.value = res.data.data
    setBreadcrumb('admin.campus.show', campus.value?.name as string)
  } catch (error) {
    console.error('Failed to load campus:', error)
  } finally {
    loading.value = false
  }
}

watch(
  () => route.params.id,
  () => fetchCampusDetails(),
  { immediate: true },
)
</script>
