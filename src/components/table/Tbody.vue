<template>
  <tbody class="z-1">
    <Tload v-if="loading" :columns="Number(cols)" />

    <tr class="" v-else-if="!data || data.length === 0" data-empty>
      <td :colspan="Number(cols)">
        <div class="flex flex-col items-center justify-center gap-2 text-slate-500">
          <Icon icon="x-circle" class="text-6xl mb-3" />
          <p class="text-lg">No data on the record.</p>
          <span>{{ errorMessage ?? 'Try adding a new record.' }} </span>
        </div>
      </td>
    </tr>
    <slot v-else />

    <tr class="bg-secondary" v-if="endOfList">
      <td :colspan="Number(cols)">- End -</td>
    </tr>
  </tbody>
</template>

<script setup lang="ts">
const props = defineProps<{ loading: boolean; data: any; errorMessage?: string }>()

const table = inject<any>('table', null)
const cols = computed(() => table.cols.value)

const tableData = computed(() => table.props.data)

const endOfList = computed(() => {
  if (table.data?.data) {
    return tableData.value?.current_page == tableData.value?.last_page
  }

  return false
})
</script>
