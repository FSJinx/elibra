<template>
  <aside class="hidden xl:block w-100 p-5 bg-background border border-border rounded-xl overflow-y-auto">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-foreground-secondary">Filters</p>
        <h2 class="mt-1 text-xl font-semibold tracking-tight">Refine results</h2>
      </div>

      <button type="button" class="text-xs text-foreground-secondary hover:text-danger transition-colors" @click="$emit('reset')">Reset</button>
    </div>

    <div class="h-px bg-border my-5"></div>

    <Form>
      <template #body>
        <Control direction="col">
          <Label id="params-campus">Campus</Label>
          <Select id="params-campus" title="Campus" v-model="params.campus">
            <Option value="">All data</Option>
            <template v-for="item in options.campuses" :key="item.id">
              <Option :value="item.id">{{ item.name }}</Option>
            </template>
          </Select>
        </Control>

        <Control direction="col">
          <Label id="params-branch">Branch</Label>
          <Select id="params-branch" title="Branch" v-model="params.branch">
            <Option value="">All branches</Option>
            <template v-for="item in campusBranches" :key="item.id">
              <Option :value="item.id">{{ item.name }}</Option>
            </template>
          </Select>
        </Control>

        <Control direction="col">
          <Label id="params-sort">Sort results</Label>
          <Select id="params-sort" title="Sort By" v-model="params.sort">
            <Option value="">Relevance</Option>
            <Option value="title">Title</Option>
            <Option value="publication_year">Publication year</Option>
          </Select>
        </Control>

        <Control direction="col">
          <Label id="params-order">Order</Label>
          <Select id="params-order" v-model="params.order">
            <Option value="asc">Ascending</Option>
            <Option value="desc">Descending</Option>
          </Select>
        </Control>

        <Button class="w-full mt-3" variant="primary" @click="$emit('apply')"> Apply filters </Button>
      </template>
    </Form>
  </aside>
</template>

<script setup lang="ts">
interface Params {
  search: string
  campus: string
  branch: string
  sort: string
  order: string
  item_type: string
  category: string
}

const params = defineModel<Params>('params', { required: true })

defineEmits<{
  apply: []
  reset: []
}>()

const options = optionStore()

watch(
  () => params.value.campus,
  () => {
    params.value.branch = ''
  },
  { flush: 'sync' },
)

const campusBranches = computed(() => {
  if (params.value.campus) {
    return (options.libraries ?? []).filter((item) => String(item.campus_id) === String(params.value.campus))
  }

  return (options.libraries ?? []).map((b) => {
    const c = options.campuses?.find((i) => String(i.id) === String(b.campus_id))
    return {
      ...b,
      name: c ? `${b.name} - ${c.name}` : b.name,
    }
  })
})
</script>

<style scoped></style>
