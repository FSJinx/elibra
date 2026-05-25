<template>
  <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 w-full">
    <div v-for="(card, index) in cards" :key="index" class="bg-white border border-neutral-100 rounded-lg p-5 shadow-sm hover:shadow-md transition flex flex-col gap-4 overflow-hidden">
      <!-- TOP ROW -->
      <div class="flex items-center justify-between">
        <span class="p-3 bg-primary rounded-sm">
          <component :is="card.icon" class="w-6 h-6 text-white" />
        </span>

        <span v-if="card.value !== card.oldValue" class="text-xs font-semibold px-2 py-1 rounded-full ml-auto" :class="card.value > card.oldValue ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
          {{ computeValue(card.oldValue, card.value) }}
        </span>
      </div>

      <div class="flex flex-col items-end mt-5">
        <!-- VALUE -->
        <div class="text-2xl font-extrabold text-neutral-800 tracking-tight">
          {{ Number(card.value).toLocaleString() }}
        </div>

        <!-- LABEL -->
        <div class="text-sm text-neutral-400">
          {{ card.name }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const computeValue = (old, val) => {
  if (old > val) {
    return `- ${Number((val / old) * 100).toFixed(1)}%`
  } else if (old < val) {
    return `+ ${Number((old / val) * 100).toFixed(1)}%`
  }
}

const cards = ref([
  {
    name: 'Total Campuses',
    value: '6',
    oldValue: '2',
    icon: 'Building2',
  },
  {
    name: 'Total Librarians',
    value: '10',
    oldValue: '11',
    icon: 'UsersRound',
  },
  {
    name: 'Total Patrons',
    value: '75233',
    oldValue: '31232',
    icon: 'User',
  },
  {
    name: 'Total Collections',
    value: '34234325',
    oldValue: '42475333',
    icon: 'LibraryBig',
  },
])
</script>

<style scoped></style>
