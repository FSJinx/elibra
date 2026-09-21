<template>
  <div class="max-h-[75dvh] overflow-y-auto">
    <Transition name="fade">
      <p class="text-danger p-3 px-5" v-if="modal?.errorMessage?.length > 0 && !modal?.loading"><Icon class="mr-2" icon="exclamation-circle" /> {{ modal?.errorMessage }}</p>
    </Transition>
    
    <div class="flex-1 flex min-h-100" v-if="modal?.loading">
      <Spinner class="m-auto text-2xl" />
    </div>

    <slot v-else />
  </div>
</template>

<script setup lang="ts">
const modal = inject<any>('modal')
</script>

<style scoped></style>
