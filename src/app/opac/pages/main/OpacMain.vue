<template>
  <main class="min-w-0 max-w-300 flex-1">
    <!-- Search Header -->
    <section class="sticky top-20 overflow-hidden rounded-xl border border-border bg-background">
      <!-- <div class="absolute -right-20 -top-20 size-60 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div> -->

      <div class="relative p-6 sm:p-8">
        <div class="max-w-2xl">
          <p class="text-[11px] font-semibold uppercase tracking-widest text-primary">Online Public Access Catalog</p>
          <h1 class="mt-1 text-3xl sm:text-4xl font-bold tracking-tight">Look for something</h1>
          <p class="mt-2 text-sm text-foreground-secondary">Search the library catalog by title, author, subject, or call number.</p>
        </div>

        <Form class="mt-6" @submit="$emit('search')">
          <Control>
            <Input id="params-search" :placeholder="`Try searching '${typedText}|'`" v-model="params.search" enable-clear />

            <Select id="opac-item-type" class="max-w-max" v-model="params.item_type">
              <Option value="">All Item Type</Option>
              <template v-for="item in itemType.itemTypes" :key="item.id">
                <Option :value="item.id">{{ item.name }}</Option>
              </template>
            </Select>

            <Select id="opac-category" class="max-w-max" v-model="params.category">
              <Option value="">All Categories</Option>
              <template v-for="item in itemTypeCategories" :key="item.id">
                <Option :value="item.id">{{ item.name }}</Option>
              </template>
            </Select>

            <Button type="submit" icon="search" variant="primary">
              <span class="hidden sm:inline">Search</span>
            </Button>
          </Control>
        </Form>
      </div>
    </section>

    <!-- Results -->
    <section v-if="loading && route.query.search" class="flex flex-col items-center justify-center min-h-100 mt-3 bg-background rounded-xl border border-dashed border-inverse/50 text-center px-5">
      <div class="flex items-center justify-center size-16 rounded-xl bg-primary/10 text-primary mb-5">
        <Icon icon="search" class="text-2xl" />
      </div>
      <h2 class="text-xl font-semibold">Searching the catalog...</h2>
      <p class="mt-2 text-sm text-foreground-secondary">Please wait while we find matching materials.</p>
    </section>

    <section v-else-if="route.query.search && libraryData.length === 0" class="flex flex-col items-center justify-center min-h-100 mt-3 rounded-xl bg-background border border-dashed border-border shadow text-center px-5">
      <div class="flex items-center justify-center size-16 rounded-xl bg-danger-soft text-danger mb-5">
        <Icon icon="search" class="text-2xl" />
      </div>
      <h2 class="text-xl font-semibold">No results found</h2>
      <p class="mt-2 max-w-md text-sm text-foreground-secondary">We couldn't find any catalog materials matching "{{ route.query.search }}".</p>
    </section>

    <section v-else class="mt-3">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 p-6 border border-border rounded-xl bg-background" v-if="route.query.search">
        <div>
          <p class="text-xs uppercase tracking-widest font-semibold text-foreground-secondary">Search results</p>

          <h2 class="mt-1 text-2xl font-semibold tracking-tight">
            <template v-if="route.query.search">
              Showing results for <span class="text-primary">"{{ route.query.search }}"</span>
            </template>
            <template v-else>Explore our library collection</template>
          </h2>

          <p class="text-sm text-foreground-secondary mt-1">Showing results for {{ route.params.search }}.</p>
        </div>

        <span class="text-sm text-foreground-secondary">{{ total }} results</span>
      </div>

      <div class="space-y-3">
        <router-link v-for="(item, index) in libraryData" :key="item.id ?? index" :to="{ name: 'opac.view', params: { id: item.item_id } }" class="block">
          <Card class="group relative flex-row items-start w-full gap-4 p-4! rounded-xl! border border-border hover:border-primary/40 hover:shadow-md transition-all duration-200 cursor-pointer">
            <div class="hidden sm:flex w-7 shrink-0 justify-center pt-2">
              <span class="text-xs font-medium text-foreground-secondary">
                {{ String(index + 1).padStart(2, '0') }}
              </span>
            </div>

            <div class="relative w-20 sm:w-24 aspect-2/3 shrink-0 rounded-lg overflow-hidden bg-primary/5 border border-border">
              <img :src="default_book" alt="" class="w-full h-full object-cover transition-transform duration-300" />
            </div>

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

            <div class="hidden sm:block shrink-0 pt-1">
              <Status class="text-xs capitalize px-3 py-1.5 rounded-full border border-current/30" :variant="parse.status(item.itemType as string)">
                {{ item.item_type }}
              </Status>
            </div>
          </Card>
        </router-link>
      </div>
    </section>
  </main>
</template>

<script setup lang="ts">
import default_book from '@/assets/images/default_book.png'
import { useSearchTyping } from '@/composables/data/useSearchTyping'

interface Params {
  search: string
  campus: string
  branch: string
  sort: string
  order: string
  item_type: string
  category: string
}

interface LibraryItem {
  id?: string | number
  item_id: string | number
  title: string
  subtitle?: string
  authors?: string[]
  publication_year?: string | number
  branch?: string
  item_type?: string
  itemType?: string
}

defineProps<{
  libraryData: LibraryItem[]
  total: number
  loading: boolean
}>()

defineEmits<{
  search: []
}>()

const params = defineModel<Params>('params', { required: true })

const { typedText } = useSearchTyping()
const route = useRoute()
const parse = useParser()

const itemType = itemTypeStore()
const itemCategory = itemCategoriesStore()
const { campuses } = campusStore()

const selectedCampus = computed(() => campuses?.find((i) => String(i.id) === params.value.campus)?.name)
const itemTypeCategories = computed(() => itemCategory.itemCategories.filter((item) => String(item.item_type_id) === String(params.value.item_type)))
</script>

<style scoped>
.animate-pulse {
  animation: pulse 1s ease-in-out;
}

@keyframes pulse {
  0%,
  100% {
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
}
</style>
