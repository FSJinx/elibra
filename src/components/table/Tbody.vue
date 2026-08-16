<template>
  <tbody>
    <Tload v-if="loading" :columns="columns" />
    <tr v-else-if="!data || data.length === 0">
      <td class="" :colspan="columns">
        <div class="flex flex-col items-center gap-2 py-10 text-slate-500 mt-50">
          <MessageCircleX class="mb-2" />
          <p class="font-semibold">No data found.</p>
          <span class="text-sm">Try adding a new record.</span>
        </div>
      </td>
    </tr>
    <slot v-else />
  </tbody>
</template>

<script setup lang="ts">
defineProps<{ loading: boolean; columns: number; data: any }>()
</script>
