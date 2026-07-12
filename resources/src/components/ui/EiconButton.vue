<template>
  <button type="button" class="inline-flex shrink-0 items-center justify-center h-10 w-10 border border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-md cursor-pointer" :title="name">
    <component :is="icon" class="h-5 w-5"></component>
  </button>
</template>

<script setup lang="ts">
interface Props {
  icon: string
  name?: string
}

const props = defineProps<Props>()
</script>

<style scoped></style>
