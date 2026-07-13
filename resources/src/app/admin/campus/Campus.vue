<template>
  <div class="flex flex-col h-full overflow-hidden">
    <div class="p-5 pb-0">
      <h2 class="text-2xl font-semibold text-slate-900">ISU Campuses</h2>
      <p class="text-sm text-slate-500">Browse and manage the campuses in ISU.</p>
    </div>

    <div class="flex items-center justify-end gap-2 px-5 py-3 border-b border-slate-200">
      <EBadge class="mr-auto border border-slate-300 bg-slate-50" radius="pill">{{ campus.campuses.length }} records</EBadge>
      <FormSearchInput id="campus-query" class="w-md bg-slate-50" v-model="query" radius="cube" placeholder="Search campus by name, code, or address" />

      <eButton @click="newCampusModal?.open()" variant="solid-hover" color="success"> + Add New </eButton>
      <IconButton icon="RefreshCcw" name="Refresh" @click="campus.refresh" />
    </div>

    <CampusTable :data="campuses" :loading="campus.loading" @view="campus.view" />
  </div>

  <CampusModal ref="newCampusModal"></CampusModal>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import Icon from '../../../components/ui/eIcon.vue'
import CampusTable from './CampusTable.vue'
import IconButton from '../../../components/ui/EiconButton.vue'
import { useCampus } from '../../../stores/campusCache.js'
import CampusModal from './NewCampusModal.vue'
import FormSearchInput from '../../../components/forms/FormSearchInput.vue'
import eButton from '../../../components/ui/eButton.vue'
import EBadge from '../../../components/ui/eBadge.vue'

const campus = useCampus()
const campuses = computed(() => campus?.campuses)
const query = ref<string>('')
const newCampusModal = ref<InstanceType<typeof CampusModal> | null>(null)

watch(query, (value) => {
  campus.campuses = []
  campus.fetchCampuses(query.value)
})

onMounted(() => {
  campus.fetchCampuses(query.value)
})
</script>

<style scoped></style>
