<template>
  <div class="my-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl text-warning font-bold uppercase tracking-wide">What's New?</h1>
      <p class="text-foreground-secondary mt-1">Discover recently added library materials for your research and leisure.</p>
    </div>

    <!-- Results Grid -->
    <div v-if="loading" class="text-center py-12 text-foreground-secondary">Loading new library materials...</div>

    <div v-else-if="data.length" class="grid grid-cols-1 xl:grid-cols-2 gap-4">
      <Card v-for="item in data" :key="item.id" class="p-4 hover:shadow-md transition-shadow border border-border bg-background rounded-xl">
        <div class="flex items-start gap-4">
          <!-- Book Cover / Thumbnail -->
          <div class="w-24 h-32 shrink-0 overflow-hidden rounded-md border border-border bg-muted">
            <img :src="default_book" :alt="item.title" class="size-full object-cover" />
          </div>

          <!-- Book Metadata -->
          <div class="flex-1 min-w-0 flex flex-col justify-between self-stretch">
            <div>
              <div class="flex items-center gap-2">
                <Icon icon="geo-alt-fill" class="text-danger" />
                <span class="text-sm">{{ useBranchStore().branches.find((i) => i.id === item.branch_id)?.name }}</span>
              </div>
              <div class="flex items-center justify-between gap-2 mb-1.5">
                <h2 class="font-bold text-base text-foreground line-clamp-1" :title="item.title">
                  {{ item.title }}
                </h2>
                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-primary/10 text-primary shrink-0">
                  {{ parseCategory(item.item_type_category_id) ?? 'Library material' }}
                </span>
              </div>

              <p class="text-xs text-foreground-secondary mt-0.5 font-medium line-clamp-1">
                {{ item.subtitle || 'No additional information' }}
              </p>
            </div>

            <!-- Detailed Info -->
            <div class="mt-3 text-xs text-foreground-secondary space-y-0.5 border-t border-border/50 pt-2">
              <p class="truncate"><span class="font-semibold text-foreground">Call No:</span> {{ item.call_number || 'N/A' }}</p>
              <p class="truncate"><span class="font-semibold text-foreground">Publication Year:</span> {{ item.publication_year || 'N/A' }}</p>
              <p class="truncate"><span class="font-semibold text-foreground">Item Type:</span> {{ item.item_type_id || 'N/A' }}</p>
            </div>
          </div>
        </div>
      </Card>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12 border border-dashed rounded-xl border-border">
      <p class="text-base font-medium text-foreground-secondary">No new library materials found.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import default_book from '@/assets/images/default_book.png'

export interface LibraryItem {
  id: number
  title: string
  subtitle: string | null
  call_number: string | null
  publication_year: number | null
  item_type_id: number | null
  item_type_category_id: number | null
  status: string | null
  [key: string]: any
}

// State
const data = ref<LibraryItem[]>([])
const loading = ref(true)
const itemCategories = useItemCategoriesStore()

async function fetchNewItems() {
  try {
    const res = await get<{ data: LibraryItem[] }>('/landing/new-items')
    data.value = res.data ?? []
  } catch (error) {
    console.error('Failed to fetch new items:', error)
    data.value = []
  } finally {
    loading.value = false
  }
}

const parseCategory = (item_type_category_id: any) => {
  return itemCategories.categories.find((i) => i.id === item_type_category_id)?.name
}

onMounted(fetchNewItems)

// Badge status color mapping
const getStatusClass = (status: LibraryItem['status']) => {
  switch (status) {
    case 'available':
      return 'bg-green-100 text-green-700 dark:bg-green-950/60 dark:text-green-400'
    case 'borrowed':
      return 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400'
    case 'reserved':
      return 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400'
    default:
      return 'bg-muted text-foreground-secondary'
  }
}
</script>

<style scoped></style>
