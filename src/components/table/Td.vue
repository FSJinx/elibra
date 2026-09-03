<template>
  <td v-if="hasData" :class="{ 'text-muted': data === null || data.length === 0 }">
    {{ data ?? nullMessage }}
  </td>

  <!-- Slot fallback kapag undefined ang data prop -->
  <td :class="[alignClass]" v-else>
    <slot />
  </td>
</template>

<script setup lang="ts">
type Align = 'left' | 'center' | 'right'

interface Props {
  data?: any | null
  nullMessage?: string
  align?: Align
}

const props = withDefaults(defineProps<Props>(), {
  nullMessage: 'No data',
  align: 'center',
})

const alignClass = computed(() => {
  const aligns: Record<Align, string> = {
    left: 'text-left',
    center: 'text-center',
    right: 'text-end',
  }

  return aligns[props.align]
})

const hasData = computed(() => {
  return props.data !== undefined
})
</script>
