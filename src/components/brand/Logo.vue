<template>
  <svg class="inline" viewBox="0 0 320 320" fill="none" xmlns="http://www.w3.org/2000/svg" :height="height ?? width" :width="width" :class="[color]">
    <rect width="190.055" height="49.0959" transform="matrix(0.830985 0.556294 -0.829066 0.559151 43.0684 100.286)" fill="currentColor" />
    <rect width="128.877" height="49.2363" transform="matrix(0.830985 0.556294 0 1 52.9047 177.602)" fill="currentColor" />
    <rect width="112.297" height="49.2363" transform="matrix(-0.830985 0.556294 0 1 253.317 186.826)" fill="currentColor" />
    <rect width="45.5654" height="190.057" transform="matrix(0.830985 0.556294 -0.829066 0.559151 159.935 21.4675)" fill="currentColor" />
    <rect width="45.5654" height="139.978" transform="matrix(0.830985 0.556294 -0.829066 0.559151 178.326 89.5498)" fill="currentColor" />
    <rect width="45.5654" height="190.057" transform="matrix(0.830985 0.556294 -0.829066 0.559151 279.771 101.691)" fill="currentColor" />
    <path class="tassle" d="M265.789 161.887L288.877 146.43V198.626L277.274 185.955L265.789 214.082V161.887Z" />
    <rect class="tassle" width="45.5654" height="27.8512" transform="matrix(0.830985 0.556294 -0.829066 0.559151 251.013 121.083)" />
  </svg>
</template>

<script setup lang="ts">
interface Icon {
  width: string
  height?: string
  color?: string
}

const props = withDefaults(defineProps<Icon>(), {
  width: '32',
  color: 'currentColor',
})
</script>

<style scoped>
.tassle {
  fill: color-mix(in srgb, currentColor 65%, white);
}
</style>
