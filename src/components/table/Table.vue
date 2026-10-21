<template>
  <section class="relative flex-1 flex flex-col bg-background border border-border rounded-xl overflow-hidden scrollbar-none">
    <!-- Table Header -->
    <div class="flex items-center gap-2 border-b border-border/70 px-5 py-4">
      <div class="mr-auto">
        <h2 class="text-xl font-semibold">{{ title }}</h2>

        <p class="mt-1 text-sm text-foreground-secondary" v-if="subtitle">{{ subtitle }}</p>
      </div>

      <p class="text-muted-foreground text-sm">Showing {{ data?.to ?? 0 }} {{ data?.to > 1 ? `records out of ${data?.total ?? 0}` : 'record' }}</p>
      <slot name="header" v-if="$slots.header" />
    </div>

    <!-- Table Body -->
    <div class="relative flex-1 shrink-0 overflow-y-auto bg-background">
      <table class="relative w-full has-data-empty:h-full text-left text-[12.5px] border-collapse" :class="[layout]">
        <slot />
      </table>
    </div>

    <!-- Table Footer -->
    <div class="flex items-center justify-between w-full sticky bottom-0 bg-background px-5 py-4 border-t border-border" v-if="$slots.footer">
      <div class="flex items-center justify-end gap-3 text-sm">
        <span>Show</span>
        <Select class="max-w-max" title="Per Page" v-model="per_page">
          <Option value="25">25</Option>
          <Option value="50">50</Option>
          <Option value="75">75</Option>
          <Option value="100">100</Option>
        </Select>
      </div>

      <div class="flex items-center gap-2">
        <Button class="hover:text-primary" v-if="data?.prev_page_url" @click="emit('prev', data.current_page - 1)">Previous</Button>
        <Button class="hover:text-primary" v-if="data?.next_page_url" @click="emit('next', data.current_page + 1)">Next</Button>
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
}

const props = withDefaults(defineProps<Props>(), {
  dataLength: 0,
  layout: 'auto',
})

const emit = defineEmits(['next', 'prev', 'per_page'])
const per_page = ref(props.data?.per_page ?? '25')

const layout = computed(() => {
  const layouts: Record<NonNullable<Props['layout']>, string> = {
    auto: 'table-auto',
    fixed: 'table-fixed',
  }

  return layouts[props.layout]
})

watch(per_page, (val) => emit('per_page', val))
</script>
