<template>
  <router-link v-if="to" :to="to" custom v-slot="{ navigate }">
    <tr class="hover cursor-pointer" @click="!ignore($event) && navigate($event)" role="button">
      <slot />
    </tr>
  </router-link>
  <tr v-else class="hover cursor-pointer" @click="!ignore($event) && emit('click', $event)" role="button">
    <slot />
  </tr>
</template>

<script setup lang="ts">
import type { RouteLocationRaw } from 'vue-router'

defineProps<{ to?: RouteLocationRaw }>()
const emit = defineEmits<{ click: [event: MouseEvent] }>()

const ignore = (e: MouseEvent) => !!(e.target as HTMLElement).closest('button, a, input, select, textarea, label, [data-stop]')
</script>
