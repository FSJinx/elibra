<template>
  <tbody class="z-1">
    <Tload v-if="loading" :columns="Number(cols)" />

    <tr class="" v-else-if="!data || data.length === 0">
      <td class="py-10" :colspan="cols">
        <div class="flex items-center justify-center gap-2 text-slate-500">
          <!-- <Icon icon="x-circle" class="text-6xl mb-3" /> -->
          <p class="text-lg">No data on the record.</p>
          <!-- <span>Try adding a new record.</span> -->
        </div>
      </td>
    </tr>
    <slot v-else />
  </tbody>
</template>

<script setup lang="ts">
defineProps<{ loading: boolean; cols: any; data: any }>()
</script>
