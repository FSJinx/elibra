```vue
<template>
  <div class="relative min-h-screen w-full bg-background">
    <div class="flex items-start gap-5 mx-auto w-full p-4 lg:p-6">
      <!-- ==================== FILTER SIDEBAR ==================== -->
      <aside class="hidden xl:block sticky top-22 w-100 shrink-0 p-5 bg-background border border-border rounded-2xl shadow-sm">
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
        <section class="relative overflow-hidden rounded-2xl border border-border bg-background shadow-sm">
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
        <section v-if="!route.query.search" class="flex flex-col items-center justify-center min-h-100 mt-5 rounded-2xl border border-dashed border-border text-center px-5">
          <div class="flex items-center justify-center size-16 rounded-2xl bg-primary/10 text-primary mb-5">
            <Icon icon="search" class="text-2xl" />
          </div>

          <h2 class="text-xl font-semibold">Start your search</h2>

          <p class="mt-2 max-w-md text-sm text-foreground-secondary">Search the catalog to discover books, research materials, references, and other resources available in the library.</p>
        </section>

        <!-- Results -->
        <section v-else class="mt-5">
          <!-- Results Header -->
          <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-4">
            <div>
              <p class="text-xs uppercase tracking-widest font-semibold text-foreground-secondary">Search results</p>

              <h2 class="mt-1 text-2xl font-semibold tracking-tight">
                Results for
                <span class="text-primary"> "{{ route.query.search }}" </span>
              </h2>

              <p class="text-sm text-foreground-secondary mt-1">Showing materials matching your search.</p>
            </div>

            <span class="text-sm text-foreground-secondary"> {{ libraryData.length }} results </span>
          </div>

          <!-- Result Cards -->
          <div class="space-y-3">
            <router-link
              v-for="(item, index) in libraryData"
              :key="item.id ?? index"
              :to="{
                name: 'opac.view',
                params: { id: item.id },
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

                      <p class="mt-1 text-sm text-foreground-secondary truncate">
                        {{ item.title }}
                      </p>
                    </div>

                    <Icon icon="arrow-up-right" class="hidden sm:block shrink-0 text-foreground-secondary opacity-0 group-hover:opacity-100 group-hover:text-primary transition-all" />
                  </div>

                  <!-- Metadata -->
                  <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-4 text-xs text-foreground-secondary">
                    <span class="inline-flex items-center gap-1.5">
                      <Icon icon="user" />
                      Robert C. Martin
                    </span>

                    <span class="inline-flex items-center gap-1.5">
                      <Icon icon="calendar" />
                      2008
                    </span>

                    <span class="inline-flex items-center gap-1.5">
                      <Icon icon="building-2" />
                      Main Campus
                    </span>
                  </div>
                </div>

                <!-- Status -->
                <div class="hidden sm:block shrink-0 pt-1">
                  <Status class="text-xs capitalize px-3 py-1.5 rounded-full border border-current/30" :variant="parse.status(item.itemType as string)">
                    {{ item.itemType }}
                  </Status>
                </div>
              </Card>
            </router-link>
          </div>
        </section>
      </main>

      <!-- ==================== SEARCH HISTORY ==================== -->
      <aside class="hidden 2xl:block sticky top-22 w-100 shrink-0 p-5 bg-background border border-border rounded-2xl shadow-sm">
        <div>
          <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-foreground-secondary">History</p>

          <h2 class="mt-1 text-xl font-semibold tracking-tight">Recent searches</h2>
        </div>

        <div class="h-px bg-border my-5"></div>

        <!-- History placeholder -->
        <div class="space-y-1">
          <button class="w-full flex items-center gap-3 p-3 rounded-lg text-left hover:bg-primary/5 transition-colors group">
            <span class="flex items-center justify-center size-8 rounded-lg bg-background border border-border">
              <Icon icon="history" class="text-sm text-foreground-secondary" />
            </span>

            <span class="flex-1 min-w-0">
              <span class="block text-sm truncate"> programming </span>

              <span class="block text-xs text-foreground-secondary mt-0.5"> 2 minutes ago </span>
            </span>

            <Icon icon="arrow-up-right" class="text-foreground-secondary opacity-0 group-hover:opacity-100 transition-opacity" />
          </button>

          <button class="w-full flex items-center gap-3 p-3 rounded-lg text-left hover:bg-primary/5 transition-colors group">
            <span class="flex items-center justify-center size-8 rounded-lg bg-background border border-border">
              <Icon icon="history" class="text-sm text-foreground-secondary" />
            </span>

            <span class="flex-1 min-w-0">
              <span class="block text-sm truncate"> Philippine history </span>

              <span class="block text-xs text-foreground-secondary mt-0.5"> Yesterday </span>
            </span>

            <Icon icon="arrow-up-right" class="text-foreground-secondary opacity-0 group-hover:opacity-100 transition-opacity" />
          </button>

          <button class="w-full flex items-center gap-3 p-3 rounded-lg text-left hover:bg-primary/5 transition-colors group">
            <span class="flex items-center justify-center size-8 rounded-lg bg-background border border-border">
              <Icon icon="history" class="text-sm text-foreground-secondary" />
            </span>

            <span class="flex-1 min-w-0">
              <span class="block text-sm truncate"> software engineering </span>

              <span class="block text-xs text-foreground-secondary mt-0.5"> Aug 28 </span>
            </span>

            <Icon icon="arrow-up-right" class="text-foreground-secondary opacity-0 group-hover:opacity-100 transition-opacity" />
          </button>
        </div>

        <button class="w-full mt-4 py-2 text-xs text-foreground-secondary hover:text-danger transition-colors">Clear search history</button>
      </aside>
    </div>
  </div>
</template>

<script setup lang="ts">
import default_book from '@/assets/images/default_book.png'
import { libraryData } from '@/app/opac/opac_dummy_data'

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

onMounted(async () => {
   await getItemTypes()
   await getItemCategories()
   await getBranches()
})


function search() {
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
</script>

<style scoped></style>
```
