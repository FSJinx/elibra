<template>
  <Table class="h-full">
    <template #head>
      <th class="w-20">No</th>
      <th class="text-left">Name</th>
      <th class="w-50">Action</th>
    </template>

    <template #body>
      <tr v-for="(campus, index) in data" :key="campus.id">
        <td>{{ index + 1 }}</td>
        <td class="text-left">{{ campus.name }}</td>
        <td><IconButton icon="Eye" :name="'View ' + campus.name" @click="$emit('view', campus.id)" /></td>
      </tr>
    </template>
  </Table>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import Spinner from '../../../components/loaders/Spinner.vue'
import Table from '../../../components/tables/Table.vue'
import IconButton from '../../../components/ui/IconButton.vue'

interface Props {
  data: any[]
  loading: boolean
}

const props = withDefaults(defineProps<Props>(), {
  data: () => [],
  loading: false,
})
</script>
