<template>
  <!-- <component :is="icon" :class="[size]"></component> -->
  <component :is="icon" class="size-[1em] inline-block shrink-0"></component>
</template>

<script setup lang="ts">
interface Props {
  icon: string
}

const props = defineProps<Props>()
</script>

<style scoped></style>
