<template>
  <Table class="h-full" :loading="loading" :length="data.length" :colspan="colspan">
    <template #head>
      <th class="w-20">No</th>
      <th class="text-left">Name</th>
      <th>Code</th>
      <th class="w-100">Address</th>
      <th>Heading</th>
      <th>Status</th>
    </template>

    <template #body>
      <tr v-for="(campus, index) in data" :key="campus.id" class="hover cursor-pointer leading-relaxed" @click="check(campus)">
        <td>{{ index + 1 }}</td>
        <td class="text-left">{{ campus.name ?? 'No name' }}</td>
        <td>{{ campus.code || 'No code yet' }}</td>
        <td>{{ campus.address }}</td>
        <td>{{ campus.heading || 'No data' }}</td>
        <td class="capitalize">
          <EBadge :class="[campus.status === 'active' ? 'bg-green-400 text-white' : 'bg-red-400 text-white']" radius="pill">{{ campus.status }}</EBadge>
        </td>
      </tr>
    </template>
  </Table>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Table from '@/components/tables/Table.vue'
import EBadge from '@/components/ui/eBadge.vue'
import router from '../../../../router/index.js'
import { useCampus } from '../../../../stores/campusCache.js'

interface Props {
  data: any[]
  loading: boolean
}

const props = withDefaults(defineProps<Props>(), {
  data: () => [],
})

const campus = useCampus()
const check = (c: any) => {
  campus.currentCampus = c
  router.push({ name: 'admin.campus.details', params: { id: c?.id } })
}

const colspan = ref(7)
</script>
