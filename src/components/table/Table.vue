<template>
  <section class="relative flex-1 flex shrink-0 rounded-xl overflow-y-auto scrollbar-none">
    <div class="flex-1" v-if="loading">
      <LogoLoader />
    </div>

    <table class="w-full text-left table-fixed text-sm border-collapse">
      <slot />
    </table>
  </section>
</template>

<script setup lang="ts">
import TableNoData from '@/components/table/TableNoData.vue'
import TableLoading from '@/components/table/TableLoading.vue'

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
