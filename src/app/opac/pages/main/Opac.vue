<template>
  <div class="relative min-h-screen w-full bg-tertiary">
    <div class="flex items-start justify-center gap-3 mx-auto w-full p-3">
      <OpacFilter v-model:params="params" @apply="search" @reset="resetFilters" />

      <OpacMain v-model:params="params" :library-data="libraryData" :total="total" :loading="loading" @search="search" />

      <OpacHistory />
    </div>
  </div>
</template>

<script setup lang="ts">
import OpacFilter from '@/app/opac/pages/main/OpacFilter.vue'
import OpacMain from '@/app/opac/pages/main/OpacMain.vue'
import OpacHistory from '@/app/opac/pages/main/OpacHistory.vue'

const auth = authStore()
const route = useRoute()
const router = useRouter()

const { results: libraryData, total, loading, search: searchOpac } = useOpacSearch()

const history = opacSearchStore()

const params = reactive({
  search: (route.query.search as string) ?? '',
  campus: (route.query.campus as string) ?? auth.user?.campus.id ?? '',
  branch: (route.query.branch as string) ?? '',
  sort: (route.query.sort as string) ?? '',
  order: (route.query.order as string) ?? 'asc',
  item_type: (route.query.item_type as string) ?? '',
  category: (route.query.category as string) ?? '',
})

watch(
  () => params.campus,
  () => {
    params.branch = ''
  },
)

async function fetchResults() {
  if (!params.search) {
    libraryData.value = []
    total.value = 0
    return
  }

  try {
    await searchOpac({
      q: params.search,
      campus_id: params.campus || undefined,
      branch_id: params.branch || undefined,
      item_type_id: params.item_type || undefined,
      item_type_category_id: params.category || undefined,
      sort: params.sort || undefined,
      order: params.order || undefined,
      per_page: 10,
    })
  } catch {
    // `error` from useOpacSearch already handles this
  }
}

async function search() {
  router.replace({
    name: 'opac',
    query: {
      search: params.search || undefined,
      campus: params.campus || undefined,
      branch: params.branch || undefined,
      item_type: params.item_type || undefined,
      category: params.category || undefined,
      sort: params.sort || undefined,
      order: params.order || undefined,
    },
  })
}

function resetFilters() {
  Object.assign(params, {
    search: '',
    campus: '',
    branch: '',
    item_type: '',
    category: '',
    sort: '',
    order: 'asc',
  })

  router.push({ name: 'opac' })
}

watch(
  () => route.query,
  async (query) => {
    params.search = (query.search as string) ?? ''
    params.campus = (query.campus as string) ?? auth.user?.library?.campus?.id ?? ''
    params.branch = (query.branch as string) ?? ''
    params.item_type = (query.item_type as string) ?? ''
    params.category = (query.category as string) ?? ''
    params.sort = (query.sort as string) ?? ''
    params.order = (query.order as string) ?? 'asc'

    if (params.search.trim()) {
      await fetchResults()
    } else {
      libraryData.value = []
      total.value = 0
    }
  },
  { immediate: true, deep: true },
)

watch(
  () => route.query.search,
  () => {
    if (route.query.search) {
      history.addHistory(params.search)
    }
  },
)

onMounted(async () => {
  const { getItemTypes } = useItemTypes()
  const { getItemCategories } = useItemCategories()
  const { getBranches } = useBranch()

  await getItemTypes()
  await getItemCategories()
  await getBranches()
})
</script>

<style scoped></style>
