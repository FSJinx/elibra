<template>
  <div class="flex-1 flex relative overflow-y-auto">
    <table class="flex-1 min-h-0 w-full text-center border-collapse">
      <thead class="sticky -top-0.5 bg-slate-50 z-10 shadow-sm">
        <tr class="tracking-wide text-sm text-slate-500 uppercase">
          <slot name="head" />
        </tr>
      </thead>

      <tbody class="overflow-y-auto">
        <TableLoading v-if="loading" :colspan="colspan" />
        <TableNoData v-else-if="length === 0" :colspan="colspan" />
        <slot name="body" v-else />
      </tbody>
    </table>
    <!-- <div class="">
    </div> -->
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import TableNoData from './TableNoData.vue'
import TableLoading from './TableLoading.vue'

interface Props {
  loading: boolean
  length: number
  colspan: any
}

const props = defineProps<Props>()
</script>
