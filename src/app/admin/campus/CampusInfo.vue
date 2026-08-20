<template>
  <!-- Loading State -->
  <div class="flex-1 flex flex-col items-center justify-center gap-5 bg-background text-xl" v-if="loading">
    <Spinner />
    <p>Fetching campus, please wait...</p>
  </div>

  <!-- Content State -->
  <div class="w-full mx-auto p-6 space-y-6" v-else-if="campus">
    <!-- Main Header Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
        <div class="space-y-1">
          <div class="flex items-center gap-3 flex-wrap">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">
              {{ campus.name }}
            </h1>
            <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-slate-100 text-slate-700 border border-slate-200">
              {{ campus.code }}
            </span>
            <!-- Status Badge -->
            <span :class="['px-2.5 py-1 text-xs font-semibold rounded-full border', campus.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200']">
              <span class="inline-block w-1.5 h-1.5 rounded-full mr-1.5" :class="campus.status === 'active' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
              {{ parse.status(campus.status) }}
            </span>
          </div>
          <p class="text-slate-500 text-sm flex items-center gap-1.5 pt-1">
            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ campus.address }}
          </p>
        </div>

        <div class="text-xs text-slate-400 space-y-1 self-start md:self-auto">
          <p>ID: #{{ campus.id }}</p>
        </div>
      </div>

      <!-- Campus Heading / Tagline -->
      <div class="pt-6">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Campus Description</h3>
        <p v-if="campus.heading" class="text-slate-700 text-base leading-relaxed">
          {{ campus.heading }}
        </p>
        <p v-else class="text-slate-400 text-sm italic">No heading or description provided for this campus.</p>
      </div>
    </div>

    <!-- Metadata Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Created Info -->
      <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Date Created</p>
          <p class="text-sm font-semibold text-slate-900 mt-0.5">
            {{ parse.formatDate(campus.created_at) }}
          </p>
        </div>
      </div>

      <!-- Updated Info -->
      <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Last Updated</p>
          <p class="text-sm font-semibold text-slate-900 mt-0.5">
            {{ parse.formatDate(campus.updated_at) }}
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Empty State (Fallback if fetch completes with no data) -->
  <div class="w-full max-w-md mx-auto my-12 p-8 text-center bg-white rounded-xl border border-slate-200 shadow-sm" v-else>
    <p class="text-slate-500 font-medium">Campus details could not be found.</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const loading = ref<boolean>(false)
const campus = ref<Campus | null>(null)

const parse = useParser()
const route = useRoute()

async function fetchCampusDetails() {
  loading.value = true
  try {
    const res = await api.get(`campus/get/${route.params?.id}`)
    campus.value = res.data.data
  } catch (error) {
    console.error('Failed to load campus:', error)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await fetchCampusDetails()
})
</script>

<style scoped></style>
