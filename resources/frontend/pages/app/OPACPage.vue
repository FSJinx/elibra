<template>
  <div class="min-h-100vh md:w-[90vw] mx-auto">
    <Card class="p-5 mb-5">
      <div class="mb-10">
        <h1 class="font-bold text-2xl mb-1">Online Public Access Catalog (OPAC)</h1>
        <p class="text-gray-500 font-light">Browse our collection of books across multiple campuses and multiple categories.</p>
      </div>

      <form class="flex flex-col gap-5">
        <div class="flex items-center gap-2 w-full">
          <div class="relative flex items-center w-full">
            <Search class="absolute ml-3 text-gray-500 h-5 w-5" />
            <input type="text" name="query" class="w-full pl-10 p-2 bg-gray-50 rounded border border-gray-200" placeholder="Search here..." v-model="query" ref="queryInput" />
            <X class="absolute right-0 mr-3 text-gray-500 h-5 w-5 cursor-pointer" v-if="query" @click="clear" />
          </div>
          <Button icon="Search" label="Search" color="primary" />
          <Button icon="Filter" label="Filter" color="primary" variant="outline" />
        </div>
        <div class="flex items-center gap-2">
          <p class="font-bold">Active Filter:</p>
          <span class="filter capitalize">
            Sort By: {{ sortOrder }}
            <XCircle class="text-primary h-4 w-4" />
          </span>
          <span class="filter capitalize" v-if="filters.campus">Campus: {{ filters.campus }} <XCircle class="text-primary h-4 w-4" /></span>
          <span class="filter capitalize" v-if="filters.material_type">Material Type: {{ filters.material_type }}<XCircle class="text-primary h-4 w-4" /></span>
          <span class="filter" v-if="filters.year_published_from || filters.year_published_to">Date Published: {{ publishing }}<XCircle class="text-primary h-4 w-4" /></span>
          <span class="ml-auto text-red-600 cursor-pointer">Clear All</span>
        </div>
      </form>
    </Card>

    <OPACDisplayList :query="query" />
  </div>
</template>

<script setup>
import BaseInput from '@/components/BaseInput.vue'
import Button from '@/components/buttons/Button.vue'
import Card from '@/components/Card.vue'
import OPACDisplayList from '@/sections/public/opac/OPACDisplayList.vue'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const queryInput = ref()
const query = ref(route.query.q || '')

const filters = ref({
  campus: 'echague',
  material_type: 'book',
  year_published_from: '2004',
  year_published_to: '2026',
  sort: 'author',
  order: 'asc',
})

const sortOrder = computed(() => {
  const order = {
    asc: 'Ascending',
    desc: 'Descending',
  }

  return `${filters.value.sort} (${order[filters.value.order]})`
})

const publishing = computed(() => {
  return filters.value.year_published_from + (filters.value.year_published_from && filters.value.year_published_to ? ' - ' : '') + filters.value.year_published_to
})

function search() {
  // alert('User searched for: ' + query.value)
}

function clear() {
  query.value = ''
  queryInput.value?.focus()
}

onMounted(() => {
  if (query.value) {
    search()
  }
})
</script>

<style scoped>
.filter {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: smaller;
  gap: 5px;
  line-height: var(--leading-snug);

  background: var(--color-slate-100);
  padding: 2px 10px;
  border-radius: 2rem;
}
</style>
