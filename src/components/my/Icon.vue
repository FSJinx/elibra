<template>
  <!-- <component :is="icon" class="size-[1em] inline-block shrink-0" v-if="icon"></component> -->
  <i class="bi" :class="`bi-${icon}`"></i>
</template>

<script setup lang="ts">
interface Props {
  icon: string
}

const props = defineProps<Props>()
</script>

<style scoped></style>
