<template>
  <Teleport to="body">
    <div v-if="visible" ref="tooltipEl" class="tooltip" role="tooltip" :style="tooltipStyle">
      {{ title }}
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const visible = ref(false)
const title = ref('')
const tooltipStyle = ref({ left: '0px', top: '0px' })
let timer: ReturnType<typeof setTimeout> | null = null

const getTooltipSize = () => {
  const tooltip = document.querySelector('.tooltip') as HTMLElement | null
  if (!tooltip) {
    return { width: 120, height: 24 }
  }

  return {
    width: tooltip.offsetWidth,
    height: tooltip.offsetHeight,
  }
}

const clampPosition = (left: number, top: number) => {
  const padding = 12
  const width = getTooltipSize().width
  const height = getTooltipSize().height
  const maxLeft = window.innerWidth - width - padding
  const maxTop = window.innerHeight - height - padding

  return {
    left: Math.min(Math.max(left, padding), Math.max(padding, maxLeft)),
    top: Math.min(Math.max(top, padding), Math.max(padding, maxTop)),
  }
}

const updatePosition = (x: number, y: number) => {
  const { left, top } = clampPosition(x + 12, y + 12)
  tooltipStyle.value = { left: `${left}px`, top: `${top}px` }
}

const showTooltip = (event: MouseEvent) => {
  const target = event.target as HTMLElement | null
  const element = target?.closest('[data-title]') as HTMLElement | null
  const text = element?.dataset.title?.trim()

  if (!element || !text) {
    return
  }

  clearTimeout(timer as ReturnType<typeof setTimeout>)
  timer = setTimeout(() => {
    title.value = text
    visible.value = true
    updatePosition(event.clientX, event.clientY)
  }, 200)
}

const hideTooltip = (event?: MouseEvent) => {
  const target = event?.target as HTMLElement | null
  const element = target?.closest('[data-title]') as HTMLElement | null
  const relatedTarget = event?.relatedTarget as HTMLElement | null

  if (element && relatedTarget && (element === relatedTarget || element.contains(relatedTarget))) {
    return
  }

  clearTimeout(timer as ReturnType<typeof setTimeout>)
  visible.value = false
  title.value = ''
}

onMounted(() => {
  document.addEventListener('mouseover', showTooltip)
  document.addEventListener('mousemove', (event) => {
    if (visible.value) {
      updatePosition(event.clientX, event.clientY)
    }
  })
  document.addEventListener('mouseout', hideTooltip)
  document.addEventListener('focusin', (event) => {
    const target = event.target as HTMLElement | null
    const text = target?.closest('[data-title]')?.getAttribute('data-title')?.trim()

    if (!text) {
      return
    }

    clearTimeout(timer as ReturnType<typeof setTimeout>)
    timer = setTimeout(() => {
      title.value = text
      visible.value = true
      const rect = target?.getBoundingClientRect()
      const x = (rect?.left ?? 0) + (rect?.width ?? 0) / 2
      const y = (rect?.top ?? 0) + (rect?.height ?? 0) + 12
      const { left, top } = clampPosition(x, y)
      tooltipStyle.value = { left: `${left}px`, top: `${top}px` }
    }, 200)
  })
  document.addEventListener('focusout', () => {
    clearTimeout(timer as ReturnType<typeof setTimeout>)
    visible.value = false
    title.value = ''
  })
})

onUnmounted(() => {
  clearTimeout(timer as ReturnType<typeof setTimeout>)
  document.removeEventListener('mouseover', showTooltip)
  document.removeEventListener('mouseout', hideTooltip)
  document.removeEventListener('mousemove', () => {})
  document.removeEventListener('focusin', () => {})
  document.removeEventListener('focusout', () => {})
})
</script>

<style scoped>
.tooltip {
  position: fixed;
  z-index: 999999;
  pointer-events: none;

  background-color: var(--color-background);
  color: var(--color-foreground);
  padding: 5px 12px;
  max-width: max-content;
  border: 1px solid var(--color-border);
  border-radius: 6px;
  font-size: 12px;
  font-weight: 500;
  white-space: nowrap;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>
