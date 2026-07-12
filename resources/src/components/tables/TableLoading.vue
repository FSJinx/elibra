<template>
  <tr class="h-full">
    <td :colspan="colspan">
      <div class="flex flex-col items-center p-10 mt-50">
        <Spinner />
        <span class="text-slate-500">Loading table, please wait...</span>
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
import Spinner from '../loaders/Spinner.vue'

interface Props {
  colspan: any
}
const props = defineProps<Props>()
</script>

<style scoped></style>
