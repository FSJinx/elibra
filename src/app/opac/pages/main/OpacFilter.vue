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
          <Label id="params-campus_id">Campus</Label>
          <Select id="params-campus_id" title="Campus" v-model="params.campus_id">
            <Option value="">All data</Option>
            <template v-for="item in options.campuses" :key="item.id">
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
const params = defineModel<Partial<OpacParams>>('params', { required: true })

const emit = defineEmits(['apply', 'reset'])
const options = optionStore()
</script>
