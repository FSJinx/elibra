<template>
  <section class="flex flex-col gap-3">
    <div class="flex items-center justify-between h-20 p-5 bg-white rounded-lg border border-gray-300">
      <div class="flex items-center gap-3">
        <Select :options="sortBy" class="w-50" />
        <Select :options="orderBy" class="w-50" />
      </div>
      <div class="flex items-center gap-2">
        <input id="search" type="text" class="p-2 border border-primary rounded leading-0" placeholder="Search here..." v-model="search" />
        <Button type="soft" label="New Campus" icon="Plus" color="green" />
      </div>
    </div>

    <Table :header="headers" :data="filteredCampuses" />
  </section>
</template>

<script setup>
import Button from '@/components/buttons/Button.vue'
import Select from '@/components/inputs/Select.vue'
import Table from '@/components/Table.vue'
import { computed, ref, watch } from 'vue'

const search = ref(null)
const showedCampuses = ref(null)

const filteredCampuses = computed(() => {
  if (!search.value) return campuses

  return campuses.filter((campus) => campus.name.toLowerCase().includes(search.value.toLowerCase()))
})

// Arrays

// #region Campus Array
const campuses = [
  { name: 'Isabela State University - Main Campus', code: 'ISU-E', head: 'Betsie M. Dela  Cruz', status: 'active' },
  { name: 'Isabela State University - Angadanan Campus', code: 'ISU-AC', head: 'Eugene G. Tobias', status: 'inactive' },
]
// #endregion

const headers = [
  { label: 'Name', key: 'name', align: 'left' },
  { label: 'Code', key: 'code' },
  { label: 'Library Director', key: 'head' },
  { label: 'Status', key: 'status' },
]

const sortBy = [
  { label: 'Sort By', value: '' },
  { label: 'Name', value: 'name' },
  { label: 'Date Added', value: 'created_at' },
  { label: 'Eto test data tangina mo ka', value: 'keke' },
]

const orderBy = [
  { label: 'Order By', value: '' },
  { label: 'Ascending', value: 'asc' },
  { label: 'Descending', value: 'desc' },
  { label: 'Eto test data tangina moadfasdfads asdflkajshdfasjdfhlakjshdflksadflakshdflkjhsaf ka', value: 'keke' },
]
</script>

<style scoped></style>
