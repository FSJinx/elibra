<template>
  <div class="card-body flex flex-col justify-center text-center gap-5 flex-1 size-full" v-if="loading">
    <Spinner/>
    {{ loadingMessage }}
  </div>
  <div class="card-body flex-1">
    <slot />
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  loading?: boolean
  loadingMessage?: string
}>(), {
  loading: false,
  loadingMessage: 'Loading, please wait...'
})
</script>
