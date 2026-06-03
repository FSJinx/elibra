<template>
  <section class="flex flex-col gap-3 w-full">
    <CampusFilterSection @search="fetchCampuses" />

    <Table class="w-full" :header="headers" :data="campuses" :isLoading="isLoading" />
  </section>
</template>

<script setup>
import Table from '@/components/Table.vue'
import api from '@/plugins/axios'
import CampusFilterSection from '@/sections/admin/management/CampusFilterSection.vue'
import { computed, onMounted, ref, watch } from 'vue'

const search = ref(null)
const isLoading = ref(false)

const campuses = ref([])
const timer = ref(null)

const headers = [
  { label: 'Name', key: 'name', align: 'left', important: true },
  { label: 'Code', key: 'code' },
  { label: 'Address', key: 'address' },
  { label: 'Status', key: 'status' },
]

function setParams(params) {
  console.log(params)
}

function fetchCampuses(params = null) {
  campuses.value = []
  isLoading.value = true

  if (timer.value) clearTimeout(timer.value)

  timer.value = setTimeout(async () => {
    await api
      .get('/campus', {
        params: {
          ...params,
        },
      })
      .then((e) => {
        campuses.value = e.data
      })
      .finally(() => {
        isLoading.value = false
      })
  }, 500)
}

fetchCampuses()
</script>

<style scoped></style>
