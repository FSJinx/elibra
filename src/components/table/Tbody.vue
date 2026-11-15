<template>
  <tbody class="z-1">
    <Tload v-if="loading" :columns="Number(cols)" />

    <tr class="" v-else-if="!data || data.length === 0" data-empty>
      <td :colspan="Number(cols)">
        <div class="flex flex-col items-center justify-center gap-2 py-5 text-slate-500">
          <Icon icon="x-circle" class="text-4xl" />
          <p class="text-lg font-light">{{ errorMessage ?? 'Try adding a new record.' }}</p>
        </div>
      </td>
    </tr>
    <template v-else>
      <slot />

      <tr class="bg-secondary/50 text-foreground-secondary font-light border-b border-border" v-if="endOfList">
        <td :colspan="Number(cols)">- Nothing follows -</td>
      </tr>
    </template>
  </tbody>
</template>

<script setup lang="ts">
const props = defineProps<{ loading: boolean; data?: any; errorMessage?: string }>()

const table = inject<any>('table', null)
const cols = computed(() => table.cols.value)

const tableData = computed<Pagination>(() => table.props.data)

const endOfList = computed(() => tableData.value?.next_page_url === null)
</script>
