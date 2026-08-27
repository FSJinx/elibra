<template><ModuleTable title="Service tickets" subtitle="Resolve questions raised by patrons and staff." :columns="['Ticket', 'Requester', 'Priority', 'Status']" :rows="rows" /></template>
<script setup lang="ts">
import ModuleTable from '@/app/librarian/modules/ModuleTable.vue'
const rows = [
  ['#T-1048', 'Mia Santos', 'High', 'Open'],
  ['#T-1047', 'Carlo Reyes', 'Normal', 'Assigned'],
  ['#T-1046', 'Ana Cruz', 'Low', 'Resolved'],
]
</script>
