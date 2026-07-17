<template>
  <tr>
    <td :colspan="colspan">
      <div class="flex flex-col items-center gap-2 p-10 text-slate-500 mt-50">
        <MessageCircleX class="mb-2" />
        <p class="font-semibold">No data found.</p>
        <span class="text-sm">Try adding a new record.</span>
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
interface Props {
  colspan: any
}

const props = defineProps<Props>()
</script>

<style scoped></style>
