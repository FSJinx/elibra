<template>
  <component :is="icon" class="size-[1em]" />
</template>

<script setup lang="ts">
interface Props {
  icon: any
}

const props = defineProps<Props>()
</script>

<style scoped></style>
