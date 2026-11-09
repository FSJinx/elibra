<template>
  <section class="relative flex-1 flex flex-col bg-background border border-border rounded-xl overflow-hidden scrollbar-none">
    <!-- Table Header -->
    <div class="flex items-center gap-2 border-b border-border/70 px-5 py-4">
      <div class="mr-auto shrink-0">
        <h2 class="text-xl font-semibold">{{ title }}</h2>

        <p class="mt-1 text-sm text-foreground-secondary" v-if="subtitle">{{ subtitle }}</p>
      </div>

      <slot name="header" v-if="$slots.header" />
    </div>

    <!-- Table Body -->
    <div ref="tableWrapper" class="relative flex-1 shrink-0 overflow-y-auto bg-background">
      <table ref="table" class="relative w-full has-data-empty:h-full text-left text-[12.5px] border-collapse" :class="[layout]">
        <slot />
      </table>
    </div>

    <!-- Table Footer -->
    <div class="flex items-center justify-between gap-2 w-full sticky bottom-0 bg-background px-5 py-4 border-t border-border" v-if="$slots.footer">
      <p class="text-sm" v-if="data?.total < 25">Showing {{ data?.data?.length ?? 0 }} of {{ data?.total }} items.</p>

      <div class="flex items-center justify-end gap-3 text-sm" v-else>
        <span>Showing</span>
        <Select class="max-w-max" title="Per Page" v-model="per_page">
          <Option value="25">25</Option>
          <Option value="50">50</Option>
          <Option value="75">75</Option>
          <Option value="100">100</Option>
        </Select>
        <span class="text-muted-foreground text-sm">{{ data?.to > 1 ? `records out of ${data?.total ?? 0}` : 'record' }}</span>
      </div>

      <div class="flex items-center gap-2" v-if="data?.last_page !== 1">
        <Button class="not-disabled:hover:text-primary" :disabled="!data?.prev_page_url" @click="changePage(data?.current_page - 1)">Previous</Button>
        <Select class="max-w-max" title="Page" v-model="page" @onChange="changePage(page)" v-if="data?.current_page">
          <Option :value="item" v-for="item in data?.last_page">{{ item }}</Option>
        </Select>
        <Button class="not-disabled:hover:text-primary" :disabled="!data?.next_page_url" @click="changePage(data?.current_page + 1)">Next</Button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
interface Props {
  data?: Pagination | undefined
  title: string
  subtitle?: string
  layout?: 'auto' | 'fixed'
  cols?: any
}

const emit = defineEmits(['page', 'per_page'])
const props = withDefaults(defineProps<Props>(), {
  dataLength: 0,
  layout: 'auto',
})

const page = ref(props.data?.current_page)
const cols = ref(0)
const table = ref<HTMLTableElement>()
const tableWrapper = ref<HTMLElement>()
const count = () => (cols.value = table.value?.querySelectorAll('thead th').length ?? 0)
const per_page = ref(props.data?.per_page ?? '25')
let mo: MutationObserver

const layout = computed(() => {
  const layouts: Record<NonNullable<Props['layout']>, string> = {
    auto: 'table-auto',
    fixed: 'table-fixed',
  }

  return layouts[props.layout]
})

function changePage(num: Number) {
  emit('page', num)
  tableWrapper.value?.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => {
  count()
  mo = new MutationObserver(count)
  mo.observe(table.value!, { childList: true, subtree: true })
})

onBeforeUnmount(() => mo?.disconnect())

watch(per_page, (val) => {
  changePage(1)
  emit('per_page', val)
})

watch(
  () => props.data?.current_page,
  (v) => (page.value = v),
  { immediate: true },
)

provide('table', { props, cols })
</script>
