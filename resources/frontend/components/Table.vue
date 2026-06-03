<template>
  <div class="w-full border border-gray-300 rounded-lg bg-white overflow-hidden max-h-full">
    <div class="max-h-140 overflow-y-auto">
      <table class="table table-fixed w-full">
        <thead class="sticky top-0 bg-gray-100 shadow">
          <tr class="text-secondary">
            <th class="p-4" v-for="head in table.header" :colspan="head.colspan ?? 1" :class="[{ 'hidden lg:table-cell': !head.important }, head.align ? alignMap[head.align] : 'text-center']" :key="head">{{ head.label }}</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Loading -->
          <tr v-if="table?.isLoading">
            <td class="text-center p-4" :colspan="table.header.length + 1">
              <LoadingAnimation class="mx-auto" />
            </td>
          </tr>
          <!-- No Data -->
          <tr v-else-if="table.data?.length <= 0">
            <td class="text-center p-4" :colspan="table.header.length + 1">No data to display.</td>
          </tr>
          <!-- With Data -->
          <tr class="border-b border-gray-200 hover:bg-gray-100" v-for="(row, index) in table.data" :key="row?.id ?? index" v-else>
            <td class="p-4 text-center" :class="[{ 'hidden lg:table-cell': !column.important }, column.align ? alignMap[column.align] : 'text-center']" v-for="column in table.header">
              <p class="" :class="[statusFormatter(column.key, row[column.key])]">{{ row[column.key] ?? 'None' }}</p>
            </td>
            <td class="p-4">
              <div class="h-full w-full flex justify-center items-center gap-2">
                <Button type="soft" icon="Eye" color="blue" />
                <Button type="soft" icon="Pencil" color="yellow" />
                <Button type="soft" icon="Trash2" color="red" />
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import statusClasses from '@/constants/statusFormatter.js'
import Button from './buttons/Button.vue'
import { computed, onMounted, ref } from 'vue'
import LoadingAnimation from './animations/LoadingAnimation.vue'

function statusFormatter(column, value) {
  if (column !== 'status') return ''

  return ['flex items-center justify-center mx-auto', 'w-20', 'h-6', 'rounded-sm', 'text-xs', 'capitalize', statusClasses[value] ?? ''].join(' ')
}

const alignMap = {
  left: 'text-start',
  center: 'text-center',
  right: 'text-end',
}

const table = defineProps({
  header: Array,
  data: Array,
  isLoading: Boolean,
})
</script>
