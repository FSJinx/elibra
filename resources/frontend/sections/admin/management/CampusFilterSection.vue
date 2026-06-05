<template>
  <div class="flex items-center justify-between h-20 p-5 bg-white rounded-lg border border-gray-300">
    <div class="flex items-center gap-3">
      <SortSelect :options="sortBy" label="Sort By" class="w-50" @value="setSort" />
    </div>
    <div class="flex items-center gap-2">
      <input id="search" type="text" class="p-2 px-4 border border-primary rounded leading-0" placeholder="Search here..." v-model="query" @input="search" />
      <Button type="soft" label="New Campus" icon="Plus" color="green" @click="newCampus?.open()" />
      <Button type="solid" icon="RefreshCw" color="green" title="Refresh" />
    </div>
  </div>

  <NewCampusModalComponent ref="newCampus" />
</template>

<script setup>
import Button from '@/components/buttons/Button.vue'
import SortSelect from '@/components/inputs/SortSelect.vue'
import NewCampusModalComponent from '@/components/modals/NewCampusModalComponent.vue'
import { ref, watch } from 'vue'

const query = ref(null)
const sort = ref(null)
const order = ref('asc')

const newCampus = ref(null)

const emit = defineEmits(['search'])

const sortBy = [
  { label: 'Default', value: '' },
  { label: 'Name', value: 'name' },
  { label: 'Date Added', value: 'created_at' },
]

// Functions

function setSort(value) {
  sort.value = value.sort
  order.value = value.order
  search()
}

function search() {
  const params = {
    query: query.value,
    sort: sort.value,
    order: order.value,
  }
  console.log('Params: ', params)

  emit('search', params)
}
</script>

<style scoped></style>
