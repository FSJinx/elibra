import { default } from '../../plugins/icons';
<template>
    <span class="min-h-2.5 min-w-2.5 bg-red-400 rounded-full" v-show="show"></span>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
    show?: boolean
}>(), {
    show: false
})
</script>

<style scoped>

</style>