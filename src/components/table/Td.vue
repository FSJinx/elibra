<template>
  <td v-if="hasData" :class="{ 'text-muted': data === null || data.length === 0 }">
    {{ data ?? 'No Data' }}
  </td>

  <!-- Slot fallback kapag undefined ang data prop -->
  <td v-else>
    <slot />
  </td>
</template>

<script setup lang="ts">
interface Props {
  data: string | null
}

const props = defineProps<Props>()

const hasData = computed(() => {
  return props.data !== undefined
})
</script>
