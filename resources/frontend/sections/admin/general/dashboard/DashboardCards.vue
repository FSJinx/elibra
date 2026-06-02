<template>
  <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 w-full">
    <DashboardCard v-for="(card, index) in cards" :key="index" :label="card.name" :old-value="card.oldValue" :value="card.value" :icon="card.icon" />
  </div>
</template>

<script setup>
import DashboardCard from '@/components/DashboardCard.vue'
import { ref } from 'vue'

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
