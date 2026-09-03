<template>
  <tbody>
    <Tload v-if="loading" :columns="cols" />

    <tr class="h-150" v-else-if="!data || data.length === 0">
      <td class="" :colspan="cols">
        <div class="flex flex-col items-center gap-2 py-10 text-slate-500">
          <Icon icon="x-circle" class="text-6xl mb-3" />
          <p class="font-semibold text-lg">No data found.</p>
          <span>Try adding a new record.</span>
        </div>
      </td>
    </tr>
    <slot v-else />
  </tbody>
</template>

<script setup lang="ts">
import { MessageCircleX } from '@lucide/vue'

defineProps<{ loading: boolean; cols: number; data: any }>()
</script>
