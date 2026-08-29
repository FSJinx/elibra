<template>
  <div class="relative flex w-full">
    <div class="relative flex items-start gap-3 mx-auto w-full max-w-[120rem] p-5">
      <!-- Sticky Sidebar 1: Filters -->
      <aside class="sticky top-20 w-120 p-5 space-y-1 bg-background border border-border shadow rounded-xl">
        <h5 class="font-semibold uppercase tracking-widest text-foreground-secondary">Filters</h5>
        <h4 class="font-semibold text-2xl">Refine your results</h4>

        <Form class="mt-5">
          <template #body>
            <Control direction="col">
              <Label id="params-campus">Campus</Label>
              <Select id="params-campus" title="Campus" v-model="params.campus">
                <Option value="">All campus</Option>
                <template v-for="item in campus.campuses">
                  <Option :value="item?.id">{{ item?.name }}</Option>
                </template>
              </Select>
            </Control>

            <Control direction="col">
              <Label id="params-sort">Sort By</Label>
              <Select id="params-sort" title="Sort By" v-model="params.sort">
                <Option value="">Relevance</Option>
                <Option value="title">Title</Option>
                <Option value="publication_year">Year Published</Option>
              </Select>
            </Control>

            <Control direction="col" title="Order By">
              <Label id="params-order">Order By</Label>
              <Select id="params-order" v-model="params.order">
                <Option value="asc">Ascending</Option>
                <Option value="desc">Descending</Option>
              </Select>
            </Control>
          </template>
        </Form>
      </aside>

      <!-- Main Body Content -->
      <section class="flex-1 p-5 bg-background border border-border shadow rounded-xl">
        <h4 class="font-semibold text-2xl mb-2">Search</h4>
        <Form class="flex items-center gap-2 mb-5" @submit="search">
          <Input id="params-search" placeholder="Search title, authors, or call number" v-model="params.search" />
          <Button type="submit" icon="search" />
        </Form>

        <div class="my-5 space-y-2">
          <h5 class="font-semibold uppercase tracking-widest text-foreground-secondary">Results for</h5>
          <span class="text-primary font-serif text-2xl">{{ $route.query.search || 'All Items' }}</span>
        </div>

        <!-- Catalog Content Placeholder -->
        <div class="h-screen"></div>
        <div class="h-screen"></div>
        <div class="h-screen"></div>
      </section>

      <!-- Sticky Sidebar 2: History -->
      <aside class="sticky top-20 w-120 p-5 space-y-1 bg-background border border-border shadow rounded-xl">
        <h5 class="font-semibold uppercase tracking-widest text-foreground-secondary">History</h5>
        <h4 class="font-semibold text-2xl">Your Recent Searches</h4>
      </aside>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const campus = campusStore()
const auth = authStore()
const route = useRoute()
const router = useRouter()

const params = reactive({
  search: (route.query.search as string) ?? '',
  campus: auth.user?.library?.campus?.id ?? '',
  sort: (route.query.sort as string) ?? '',
  order: (route.query.order as string) ?? 'asc',
})

function search() {
  // Fixed: query instead of params, so URL becomes /opac?search=...&campus=...
  router.replace({ name: 'opac', query: { ...params } })
}
</script>

<style scoped></style>
