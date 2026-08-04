<template>
  <tr v-for="i in 10">
    <td v-for="c in columns" :key="c">
      <div class="h-5">
        <Skeleton />
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
defineProps<{ columns: number }>()
</script>

<style scoped></style>
