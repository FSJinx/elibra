<template>
  <tbody class="z-1">
    <Tload v-if="loading" :columns="Number(cols ?? table.cols)" />

    <tr class="" v-else-if="!data || data.length === 0" data-empty>
      <td :colspan="cols">
        <div class="flex flex-col items-center justify-center gap-2 text-slate-500">
          <Icon icon="x-circle" class="text-6xl mb-3" />
          <p class="text-lg">No data on the record.</p>
          <span>{{ errorMessage ?? 'Try adding a new record.' }} </span>
        </div>
      </td>
    </tr>
    <slot v-else />
    <tr class="bg-secondary" v-if="table?.data?.current_page == table?.data?.last_page">
      <td :colspan="cols ?? table?.cols">- End -</td>
    </tr>
  </tbody>
</template>

<script setup lang="ts">
defineProps<{ loading: boolean; cols?: any; data: any; errorMessage?: string }>()

const table = inject<any>('table', null)
</script>
