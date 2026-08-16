<template>
  <td v-if="hasData" :class="{ 'text-muted': data === null || data.length === 0 }">
    {{ data ?? nullMessage }}
  </td>

  <!-- Slot fallback kapag undefined ang data prop -->
  <td v-else>
    <slot />
  </td>
</template>

<script setup lang="ts">
interface Props {
  data?: any | null
  nullMessage?: string
}

const props = withDefaults(defineProps<Props>(), {
  nullMessage: 'No data',
})

const hasData = computed(() => {
  return props.data !== undefined
})
</script>
