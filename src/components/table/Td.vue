<template>
  <td class="text-wrap space-y-2 leading-relaxed" v-if="props.data !== undefined" :class="{ 'text-muted-foreground/50': data === null || data.length === 0 }">
    {{ data ?? nullMessage }}
  </td>

  <!-- Slot fallback kapag undefined ang data prop -->
  <td class="space-y-2" :class="[alignClass]" v-else>
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
</script>
