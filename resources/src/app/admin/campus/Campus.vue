<template>
  <div class="flex flex-col h-full overflow-hidden">
    <div class="p-7 pb-0">
      <h2 class="text-2xl font-semibold text-slate-900">ISU Campuses</h2>
      <p class="text-sm text-slate-500">Browse and manage the campuses in ISU.</p>
    </div>

    <div class="flex items-center justify-end gap-2 px-5 py-3 border-b border-slate-200">
      <p class="text-sm text-slate-500 mr-auto rounded-full border border-slate-300 p-1 px-3 bg-slate-100">{{ campus.campuses.length }} records</p>
      <input name="academic-query" id="academic-query" type="text" class="w-md px-4 h-11 bg-slate-50 border border-slate-300 rounded-md" placeholder="Search by title, call number, researcher" />
      <Button class="bg-primary text-white h-11">
        <Icon icon="Plus" size="small"></Icon>
        <span>Add New</span>
      </Button>
      <IconButton icon="RefreshCcw" name="Refresh" @click="campus.refresh" />
    </div>

    <CampusTable :data="campus.campuses" :loading="loading" @view="campus.view" />
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import Button from '../../../components/ui/Button.vue'
import Icon from '../../../components/ui/Icon.vue'
import CampusTable from './CampusTable.vue'
import IconButton from '../../../components/ui/IconButton.vue'
import { useCampus } from '../../../stores/campusCache.js'

const campus = useCampus()
const loading = campus.loading

onMounted(() => {
  campus.fetchCampuses()
})
</script>

<style scoped></style>
