<template>
  <div class="drawer-body flex-1 w-full overflow-y-auto p-5">
    <div class="flex-1 flex min-h-full" v-if="drawer?.loading">
      <Spinner class="m-auto text-2xl" />
    </div>

    <slot v-else />
  </div>
</template>

<script setup lang="ts">
const drawer = inject<any>('drawer', null)
</script>

<style scoped></style>
