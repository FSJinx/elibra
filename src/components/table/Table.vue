<template>
  <section class="relative flex-1 flex shrink-0 rounded-xl border border-border overflow-y-auto scrollbar-none">
    <Tload v-if="loading" />

    <table class="w-full text-left table-fixed text-sm border-collapse">
      <slot />
    </table>
  </section>
</template>

<script setup lang="ts">
import Tload from '@/components/table/Tload.vue'

interface Props {
  loading?: boolean
  length?: number
  colspan?: any
}

const props = defineProps<Props>()
</script>

<style scoped>
th {
  padding: 0.75rem 1rem;
}
</style>
