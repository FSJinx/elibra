<template>
  <main class="flex flex-col p-10 gap-5 w-full max-w-6xl bg-background border border-border rounded-xl overflow-hidden">
    <section class="flex flex-col gap-5">
      <div class="max-w-2xl">
        <p class="text-[11px] font-semibold uppercase tracking-widest text-primary">Online Public Access Catalog</p>
        <h1 class="mt-1 text-3xl sm:text-4xl font-bold tracking-tight">Look for something</h1>
        <p class="mt-2 text-sm text-foreground-secondary">Search the library catalog by title, author, subject, or call number.</p>
      </div>

      <Form class="" @submit="$emit('search')">
        <div class="flex items-center gap-3">
          <Input id="params-search" :placeholder="`Try searching '${typedText}|'`" v-model="params.search" enable-clear />

          <Select id="opac-item-type" v-model="params.item_type" class="max-w-max">
            <Option value="">All Item Type</Option>
            <template v-for="item in itemType.itemTypes" :key="item.id">
              <Option :value="item.id">{{ item.name }}</Option>
            </template>
          </Select>

          <Select id="opac-category" v-model="params.category" class="max-w-max">
            <Option value="">All Categories</Option>
            <template v-for="item in itemTypeCategories" :key="item.id">
              <Option :value="item.id">{{ item.name }}</Option>
            </template>
          </Select>

          <Button type="submit" icon="search" variant="primary">
            <span class="hidden sm:inline">Search</span>
          </Button>
        </div>
      </Form>
    </section>

    <div class="flex-1 flex flex-col overflow-y-auto border border-border divide-y divide-border rounded-xl">
      <!-- SEARCHING -->
      <section v-if="loading && route.query.search" class="flex-1 flex flex-col items-center justify-center min-h-100 bg-secondary text-center">
        <div class="flex items-center justify-center size-16 rounded-xl bg-primary/10 text-primary mb-5">
          <Icon icon="search" class="text-2xl" />
        </div>
        <h2 class="text-xl font-semibold">Searching the catalog...</h2>
        <p class="mt-2 text-sm text-foreground-secondary">Please wait while we find matching materials.</p>
      </section>

      <!-- NO RESULT -->
      <section v-else-if="route.query.search && libraryData.length === 0" class="flex-1 flex flex-col items-center justify-center min-h-100 bg-secondary text-center px-5">
        <div class="flex items-center justify-center size-16 rounded-xl bg-danger-soft text-danger mb-5">
          <Icon icon="search" class="text-2xl" />
        </div>
        <h2 class="text-xl font-semibold">No results found</h2>
        <p class="mt-2 max-w-md text-sm text-foreground-secondary">We couldn't find any catalog materials matching "{{ route.query.search }}".</p>
      </section>

      <router-link v-for="(item, index) in libraryData" :key="item.id ?? index" :to="{ name: 'opac.view', params: { id: item.item_id } }">
        <div class="group flex items-start p-5 gap-5 hover:bg-primary-soft/35 transition-all duration-300">
          <div class="border border-border rounded-xl contain-content">
            <img :src="default_book" alt="" class="max-w-25" />
          </div>

          <div class="flex-1 min-w-0 py-1">
            <div class="flex items-start gap-3">
              <div class="min-w-0 flex-1">
                <h3 class="font-semibold text-lg leading-snug truncate group-hover:text-primary transition-colors">{{ index + 1 }} {{ item.title }}</h3>
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
        </div>
      </router-link>
    </div>
  </main>
</template>

<script setup lang="ts">
import default_book from '@/assets/images/default_book.png'

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

const itemType = useItemTypeStore()
const itemCategory = useItemCategoriesStore()
const { campuses } = useCampusStore()

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
