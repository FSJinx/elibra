<template>
  <div class="border border-gray-300 rounded-lg bg-white overflow-hidden">
    <table class="table-auto w-full">
      <thead>
        <tr class="bg-neutral-100 border-b border-gray-300 text-secondary">
          <th class="p-4" v-for="head in table.header" :class="([head.colspan], head.align ? alignMap[head.align] : 'text-center')" :key="head">{{ head.label }}</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b border-gray-200" v-for="(row, index) in table.data" :key="row.id ?? index">
          <td class="p-4 text-center" :class="column.align ? alignMap[column.align] : 'text-center'" v-for="column in table.header">
            <p class="" :class="[statusFormatter(column.key, row[column.key])]">{{ row[column.key] }}</p>
          </td>
          <td class="flex justify-center items-center gap-2 p-4">
            <Button type="soft" icon="Eye" color="blue" />
            <Button type="soft" icon="Pencil" color="yellow" />
            <Button type="soft" icon="Trash2" color="red" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import statusClasses from '@/constants/statusFormatter.js'
import Button from './buttons/Button.vue'

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
})
</script>
