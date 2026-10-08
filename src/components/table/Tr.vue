<template>
  <router-link :to="to" custom v-slot="{ navigate }">
    <tr class="hover cursor-pointer" @click="navigate" role="button">
      <slot />
    </tr>
  </router-link>
</template>

<script setup lang="ts">
import { RouteLocationRaw } from 'vue-router'

defineProps<{
  to: RouteLocationRaw
}>()
</script>

<style scoped></style>
