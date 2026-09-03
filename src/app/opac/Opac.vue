<template>
  <div class="relative min-h-screen w-full bg-secondary">
    <div class="flex items-start gap-5 mx-auto w-full p-4 lg:p-6">
      <!-- ==================== FILTER SIDEBAR ==================== -->
      <aside class="hidden xl:block sticky top-22 w-100 shrink-0 p-5 bg-background border border-border rounded-2xl">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-foreground-secondary">Filters</p>

            <h2 class="mt-1 text-xl font-semibold tracking-tight">Refine results</h2>
          </div>

          <button type="button" class="text-xs text-foreground-secondary hover:text-danger transition-colors" @click="resetFilters">Reset</button>
        </div>

        <div class="h-px bg-border my-5"></div>

        <Form>
          <template #body>
            <!-- Campus -->
            <Control direction="col">
              <Label id="params-campus">Campus</Label>

              <Select id="params-campus" title="Campus" v-model="params.campus">
                <Option value="">All campuses</Option>

                <template v-for="(item, index) in campus.campuses" :key="index">
                  <Option :value="item.id">
                    {{ item.name }}
                  </Option>
                </template>
              </Select>
            </Control>

            <!-- Branch -->
            <Control direction="col">
              <Label id="params-branch">Branch</Label>

              <Select id="params-branch" title="Branch" v-model="params.branch">
                <Option value="">All branches</Option>

                <template v-for="item in campusBranches" :key="item.id">
                  <Option :value="item.id">
                    {{ item.name }}
                  </Option>
                </template>
              </Select>
            </Control>

            <!-- Sort -->
            <Control direction="col">
              <Label id="params-sort">Sort results</Label>

              <Select id="params-sort" title="Sort By" v-model="params.sort">
                <Option value="">Relevance</Option>
                <Option value="title">Title</Option>
                <Option value="publication_year"> Publication year </Option>
              </Select>
            </Control>

            <!-- Order -->
            <Control direction="col">
              <Label id="params-order">Order</Label>

              <Select id="params-order" v-model="params.order">
                <Option value="asc">Ascending</Option>
                <Option value="desc">Descending</Option>
              </Select>
            </Control>

            <Button class="w-full mt-3" variant="primary" @click="search"> Apply filters </Button>
          </template>
        </Form>
      </aside>

      <!-- ==================== MAIN CONTENT ==================== -->
      <main class="min-w-0 flex-1">
        <!-- Search Header -->
        <section class="relative overflow-hidden rounded-xl border border-border bg-background">
          <!-- Decorative background -->
          <div class="absolute -right-20 -top-20 size-60 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>

          <div class="relative p-6 sm:p-8">
            <div class="max-w-2xl">
              <p class="text-[11px] font-semibold uppercase tracking-widest text-primary">Online Public Access Catalog</p>

              <h1 class="mt-1 text-3xl sm:text-4xl font-semibold tracking-tight">Look for something</h1>

              <p class="mt-2 text-sm text-foreground-secondary">Search the library catalog by title, author, subject, or call number.</p>
            </div>

            <!-- Search -->
            <Form class="mt-6" @submit="search">
              <Control>
                <Input id="params-search" class="flex-1 border-0! rounded-none! shadow-none!" placeholder="Search title, author, subject, or call number..." v-model="params.search" enable-clear />

                <Select id="opac-item-type" class="max-w-max" v-model="params.item_type">
                  <Option value="">All Item Type</Option>

                  <template v-for="item in itemType.itemTypes" :key="item.id">
                    <Option :value="item.id">
                      {{ item.name }}
                    </Option>
                  </template>
                </Select>
                <Select id="opac-item-type" class="max-w-max" v-model="params.category">
                  <Option value="">All Categories</Option>

                  <template v-for="item in itemTypeCategories" :key="item.id">
                    <Option :value="item.id">
                      {{ item.name }}
                    </Option>
                  </template>
                </Select>

                <Button type="submit" icon="search" variant="primary">
                  <span class="hidden sm:inline">Search</span>
                </Button>
              </Control>
            </Form>

            <!-- Active Filters -->
            <div v-if="params.campus || params.branch || params.sort" class="flex flex-wrap items-center gap-2 mt-4">
              <span class="text-xs text-foreground-secondary"> Filters: </span>

              <span v-if="params.campus" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium"> Campus selected </span>

              <span v-if="params.branch" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium"> Branch selected </span>

              <span v-if="params.sort" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium capitalize">
                {{ params.sort.replace('_', ' ') }}
              </span>
            </div>
          </div>
        </section>

        <!-- ==================== RESULTS ==================== -->

        <!-- Empty State -->
        <!-- <section v-if="!route.query.search" class="flex flex-col items-center justify-center min-h-100 mt-5 rounded-2xl border border-dashed border-border text-center px-5">
          <div class="flex items-center justify-center size-16 rounded-2xl bg-primary/10 text-primary mb-5">
            <Icon icon="search" class="text-2xl" />
          </div>

          <h2 class="text-xl font-semibold">Start your search</h2>

          <p class="mt-2 max-w-md text-sm text-foreground-secondary">Search the catalog to discover books, research materials, references, and other resources available in the library.</p>
        </section> -->
        <section
          v-if="loading && route.query.search"
          class="flex flex-col items-center justify-center min-h-100 mt-5 rounded-2xl border border-dashed border-border text-center px-5"
        >
          <div class="flex items-center justify-center size-16 rounded-2xl bg-primary/10 text-primary mb-5">
            <Icon icon="search" class="text-2xl" />
          </div>

          <h2 class="text-xl font-semibold">
            Searching the catalog...
          </h2>

          <p class="mt-2 text-sm text-foreground-secondary">
            Please wait while we find matching materials.
          </p>
        </section>
        <section
          v-else-if="route.query.search && libraryData.length === 0"
          class="flex flex-col items-center justify-center min-h-100 mt-5 rounded-2xl border border-dashed border-border text-center px-5"
        >
          <div class="flex items-center justify-center size-16 rounded-2xl bg-primary/10 text-primary mb-5">
            <Icon icon="search-x" class="text-2xl" />
          </div>

          <h2 class="text-xl font-semibold">
            No results found
          </h2>

          <p class="mt-2 max-w-md text-sm text-foreground-secondary">
            We couldn't find any catalog materials matching
            "{{ route.query.search }}".
          </p>
        </section>

        <!-- Results -->
        <section v-else class="mt-5">
          <!-- Results Header -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 p-6 border border-border rounded-xl bg-background">
            <div>
              <p class="text-xs uppercase tracking-widest font-semibold text-foreground-secondary">Search results</p>

            <h2 class="mt-1 text-2xl font-semibold tracking-tight">
              <template v-if="route.query.search">
                Results for
                <span class="text-primary">"{{ route.query.search }}"</span>
              </template>

              <template v-else>
                Explore our library collection
              </template>
            </h2>

            <p class="text-sm text-foreground-secondary mt-1" v-if="route.query.search">
              {{ route.query.search
                  ? 'Showing materials matching your search.'
                  : 'Browse books and other materials available in the library.'
              }}
            </p>
            <p v-else>
              <span>{{ typedText }}</span><span class="animate-pulse">|</span></p>
          </div>

          <span class="text-sm text-foreground-secondary"> {{ total }} results </span>
        </div>

          <!-- Result Cards -->
          <div class="space-y-3">
            <router-link
              v-for="(item, index) in libraryData"
              :key="item.id ?? index"
              :to="{
                name: 'opac.view',
                params: { id: item.item_id },
              }"
              class="block"
            >
              <Card class="group relative flex-row items-start w-full gap-4 p-4! rounded-xl! border border-border hover:border-primary/40 hover:shadow-md transition-all duration-200 cursor-pointer">
                <!-- Number -->
                <div class="hidden sm:flex w-7 shrink-0 justify-center pt-2">
                  <span class="text-xs font-medium text-foreground-secondary">
                    {{ String(index + 1).padStart(2, '0') }}
                  </span>
                </div>

                <!-- Cover -->
                <div class="relative w-20 sm:w-24 aspect-2/3 shrink-0 rounded-lg overflow-hidden bg-primary/5 border border-border">
                  <img :src="default_book" alt="" class="w-full h-full object-cover transition-transform duration-300" />
                </div>

                <!-- Information -->
                <div class="flex-1 min-w-0 py-1">
                  <div class="flex items-start gap-3">
                    <div class="min-w-0 flex-1">
                      <h3 class="font-semibold text-lg leading-snug truncate group-hover:text-primary transition-colors">
                        {{ item.title }}
                      </h3>

                      <p v-if="item.subtitle" class="mt-1 text-sm text-foreground-secondary truncate">
                        {{ item.subtitle }}
                      </p>
                    </div>

                    <Icon icon="arrow-up-right" class="hidden sm:block shrink-0 text-foreground-secondary opacity-0 group-hover:opacity-100 group-hover:text-primary transition-all" />
                  </div>

                  <!-- Metadata -->
                  <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-4 text-xs text-foreground-secondary">
                    <span class="inline-flex items-center gap-1.5">
                      <Icon icon="user" />
                      {{ item.authors?.join(', ') || 'No Author' }}
                    </span>

                    <span class="inline-flex items-center gap-1.5">
                      <Icon icon="calendar" />
                      {{ item.publication_year || 'Unknown Year' }}
                    </span>

                    <span class="inline-flex items-center gap-1.5">
                      <Icon icon="building-2" />
                      {{ item.branch || 'Unknown Branch' }}
                    </span>
                  </div>
                </div>

                <!-- Status -->
                <div class="hidden sm:block shrink-0 pt-1">
                  <Status class="text-xs capitalize px-3 py-1.5 rounded-full border border-current/30" :variant="parse.status(item.itemType as string)">
                    {{ item.item_type  }}
                  </Status>
                </div>
              </Card>
            </router-link>
          </div>
        </section>
      </main>

      <!-- ==================== SEARCH HISTORY ==================== -->
      <OpacHistory />
    </div>
  </div>
</template>

<script setup lang="ts">
import default_book from '@/assets/images/default_book.png'
import { useSearchTyping } from '@/composables/data/useSearchTyping'

const { typedText } = useSearchTyping()
import OpacHistory from '@/app/opac/sections/OpacHistory.vue'

const campus = campusStore()
const branch = branchStore()
const itemType = itemTypeStore()
const itemCategory = itemCategoriesStore()

const auth = authStore()
const route = useRoute()
const router = useRouter()
const parse = useParser()

const { getItemTypes } = useItemTypes()
const { getItemCategories } = useItemCategories()
const { getBranches } = useBranch()

const {
  results: libraryData,
  total,
  loading, 
  error,
  search:searchOpac
} = useOpacSearch()
const history = opacSearchStore()

const params = reactive({
  search: (route.query.search as string) ?? '',
  campus: (route.query.campus as string) ?? auth.user?.library?.campus?.id ?? '',
  branch: (route.query.branch as string) ?? '',
  sort: (route.query.sort as string) ?? '',
  order: (route.query.order as string) ?? 'asc',
  item_type: (route.query.item_type as string) ?? '',
  category: (route.query.category as string) ?? '',
})

const campusBranches = computed(() =>
  branch.branches.filter(
    item => String(item.campus_id) === String(params.campus)
  )
)

const itemTypeCategories = computed(() =>
  itemCategory.itemCategories.filter(
    item => String(item.item_type_id) === String(params.item_type)
  )   
) 

watch(
  () => params.campus,
  () => {
    params.branch = ''
  }
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
    // console.error('Error fetching search results:', error)
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

  router.push({
    name: 'opac',
  })
}

watch(
  () => route.query,
  async query => {
    params.search = (query.search as string) ?? ''

    params.campus = (query.campus as string) ?? auth.user?.library?.campus?.id ??''

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
  { immediate: true, deep: true, }
)

onMounted(async () => {
   await getItemTypes()
   await getItemCategories()
   await getBranches()
})

watch(
  () => route.query.search,
  (newQuery) => {
    if (route.query.search !== '') {
      history.addHistory(params.search)
    }
  },
)
</script>

<style scoped></style>
