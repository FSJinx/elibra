<template>
  <Table class="h-full" :loading="loading" :length="data.length" :colspan="colspan">
    <template #head>
      <th class="w-20">No</th>
      <th class="text-left">Name</th>
      <th>Code</th>
      <th>Address</th>
      <th>Heading</th>
      <th>Status</th>
      <th class="w-50">Action</th>
    </template>

    <template #body>
      <tr v-for="(campus, index) in data" :key="campus.id" class="hover">
        <td>{{ index + 1 }}</td>
        <td class="text-left">{{ campus.name ?? 'No name' }}</td>
        <td>{{ campus.code || 'No assigned code' }}</td>
        <td>{{ campus.address }}</td>
        <td>{{ campus.heading || 'No data' }}</td>
        <td class="capitalize">
          <EBadge :class="[campus.status === 'active' ? 'bg-green-400 text-white' : 'bg-red-400 text-white']" radius="pill">{{ campus.status }}</EBadge>
        </td>
        <td><IconButton icon="Eye" :name="'View ' + campus.name" @click="$emit('view', campus.id)" /></td>
      </tr>
    </template>
  </Table>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Table from '../../../components/tables/Table.vue'
import IconButton from '../../../components/ui/EiconButton.vue'
import Eicon from '../../../components/ui/Eicon.vue'
import EBadge from '../../../components/ui/eBadge.vue'

interface Props {
  data: any[]
  loading: boolean
}

const props = withDefaults(defineProps<Props>(), {
  data: () => [],
})

const colspan = ref(7)
</script>
