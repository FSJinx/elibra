<template>
  <main class="flex flex-col p-10 pb-5 gap-5 w-full max-w-6xl bg-background border border-border rounded-xl overflow-hidden">
    <section class="flex flex-col gap-5">
      <div class="max-w-2xl">
        <p class="text-[11px] font-semibold uppercase tracking-widest text-primary">Online Public Access Catalog</p>
        <h1 class="mt-1 text-3xl sm:text-4xl font-bold tracking-tight">Look for something</h1>
        <p class="mt-2 text-sm text-foreground-secondary">Search the library catalog by title, author, subject, or call number.</p>
      </div>

      <Form class="" @submit="$emit('search')">
        <div class="flex items-center gap-3">
          <Input id="params-search" :placeholder="`Try searching ${typedText}|`" v-model="params.query" enable-clear />

          <Select id="opac-item-type" v-model="params.item_type_id" class="max-w-max">
            <Option value="">All Item Type</Option>
            <Option :value="item.id" v-for="item in options.item_types">{{ item.name }}</Option>
          </Select>

          <Select id="opac-category" v-model="params.item_type_category_id" class="max-w-max">
            <Option value="">All Categories</Option>
            <Option :value="item.id" v-for="item in options.categories?.filter((i) => i.item_type_id === params.item_type_id)">{{ item.name }}</Option>
          </Select>

          <Button type="submit" icon="search" variant="primary">
            <span class="hidden sm:inline">Search</span>
          </Button>
        </div>
      </Form>
    </section>

    <Alert v-if="error" variant="danger">{{ error }}</Alert>

    <p v-if="route.query.query?.length && !loading">
      {{ props.data?.total ?? 0 }} record(s) found for "<span class="font-bold">{{ route.query.query }}</span
      >"
    </p>

    <div class="flex-1 flex flex-col overflow-y-auto border border-border divide-y divide-border rounded-xl shadow">
      <!-- SEARCHING -->
      <section v-if="loading" class="flex-1 flex flex-col items-center justify-center min-h-100 bg-secondary text-center">
        <div class="flex items-center justify-center size-16 rounded-xl bg-primary/10 text-primary mb-5">
          <Icon icon="search" class="text-2xl" />
        </div>
        <h2 class="text-xl font-semibold">Searching the catalog...</h2>
        <p class="mt-2 text-sm text-foreground-secondary">Please wait while we find matching materials.</p>
      </section>

      <!-- NO RESULT -->
      <section v-else-if="!error && opac.data?.length === 0" class="flex-1 flex flex-col items-center justify-center min-h-100 bg-secondary text-center px-5">
        <div class="flex items-center justify-center size-16 rounded-xl bg-danger-soft text-danger mb-5">
          <Icon icon="search" class="text-2xl" />
        </div>
        <h2 class="text-xl font-semibold">No results found</h2>
        <p v-if="route.query.search" class="mt-2 max-w-md text-sm text-foreground-secondary">We couldn't find any catalog materials matching "{{ route.query.search }}".</p>
        <p v-else class="mt-2 max-w-md text-sm text-foreground-secondary">No published materials match the selected filters. Try another campus or library, or check back later.</p>
      </section>

      <template v-else>
        <router-link v-for="(item, index) in opac.data" :key="item.id ?? index" :to="{ name: 'opac.view', params: { id: item.id } }" @click="opac.currentData = item">
          <div class="group flex items-start p-5 gap-5 hover:bg-primary-soft/35 transition-all duration-300">
            <div class="border border-border rounded-xl contain-content w-25">
              <img :src="image(item.cover_media?.file_path)" :alt="`Item cover for ${item.title}`" class="w-full" loading="lazy" />
            </div>

            <div class="flex-1 min-w-0 py-1">
              <!-- Item Title -->
              <div class="flex items-start gap-3">
                <div class="min-w-0 flex-1">
                  <h3 class="font-semibold text-lg leading-snug truncate group-hover:text-primary transition-colors">{{ (data?.from ?? 1) + index }}. {{ item.title }}</h3>
                  <span class="inline-flex items-center text-sm gap-1.5">
                    by
                    <template v-if="item.authors">{{ item.authors?.[0].name?.author }}{{ item.authors.length > 1 ? ', et al' : '' }}</template>
                    <template v-else>No author</template>

                    <span class="inline-flex items-center gap-1.5" v-if="item.publication_year">
                      <Icon icon="calendar" />
                      <span class="font-semibold">{{ item.publication_year || 'Unknown Publication Year' }}</span>
                    </span>
                  </span>
                </div>

                <Icon icon="arrow-up-right" class="hidden sm:block shrink-0 text-foreground-secondary opacity-0 group-hover:opacity-100 group-hover:text-primary transition-all" />
              </div>

              <div class="mt-5 text-[0.85rem] space-y-1 text-foreground-secondary">
                <p>
                  Item Type: <span class="font-semibold">{{ item.item_type?.name }} - {{ item.item_type_category?.name }}</span>
                </p>
                <p>
                  Call Number: <span class="font-semibold">{{ item.call_number }}</span>
                </p>
                <p>
                  Location: <span class="font-semibold">{{ item.library?.name || 'Unknown Library' }} - {{ item.library?.campus?.name }}</span>
                </p>
              </div>
            </div>
          </div>
        </router-link>
      </template>
    </div>
    <div class="flex items-center gap-2">
      <div class="flex-1 flex items-center gap-3 mr-auto shrink-0">
        Showing
        <Select class="max-w-max" title="Item Per Page" v-model="params.per_page" @onChange="emit('search')">
          <Option value="25">25</Option>
          <Option value="50">50</Option>
          <Option value="75">75</Option>
          <Option value="100">100</Option>
        </Select>
        item of {{ data?.total }}
      </div>

      <Button @click="page(data?.current_page - 1)" v-if="props.data?.prev_page_url">Previous</Button>
      <Button @click="page(data?.current_page + 1)" v-if="props.data?.next_page_url">Next</Button>
    </div>
  </main>
</template>

<script setup lang="ts">
import default_book from '@/assets/images/default_book.png'

const route = useRoute()
const parse = useParser()
const opac = opacStore()
const options = optionStore()
const { typedText } = useSearchTyping()

const emit = defineEmits(['search'])
const props = withDefaults(
  defineProps<{
    loading: boolean
    error?: string
    data: Pagination | undefined
  }>(),
  {
    loading: false,
  },
)

const params = defineModel<Partial<OpacParams>>('params', { required: true })
const image = computed(() => (media: any) => (media ? `${storageRoute}/${media}` : default_book))

function page(page = 0) {
  params.value.page = page
  emit('search')
}
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
