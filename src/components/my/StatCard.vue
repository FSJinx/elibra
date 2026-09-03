<template>
  <div class="flex min-h-22 flex-col gap-2 rounded-xl border border-border bg-white p-5 transition-colors duration-150" :class="variantClasses" :data-title="`${label}: ${value ? value.toLocaleString() : 'No data'}`">
    <div v-if="isLoading" class="flex flex-col gap-3">
      <div class="h-3 w-2/5 animate-shimmer rounded bg-linear-to-r from-gray-200 via-gray-100 to-gray-200 bg-size-[200%_100%]" />
      <div class="h-6 w-3/5 animate-shimmer rounded bg-linear-to-r from-gray-200 via-gray-100 to-gray-200 bg-size-[200%_100%]" />
    </div>

    <template v-else>
      <div class="flex items-start justify-between gap-2 mb-3">
        <p class="m-0 text-muted-foreground">{{ label }}</p>
        <div v-if="icon" class="flex shrink-0 items-center justify-center size-13 rounded-full" :class="[iconDesign]">
          <component :is="icon" v-if="typeof icon === 'object'" />
          <span :class="`text-${variant}`" v-else>
            <Icon :icon="icon" />
          </span>
        </div>
      </div>

      <div class="flex flex-wrap items-baseline gap-2 mt-auto">
        <span v-if="trend !== undefined && trend !== null" class="rounded-md px-1.5 py-0.5 text-[0.8125rem] font-medium" :class="trendClasses">
          {{ trendDirection === 'up' ? '↑' : trendDirection === 'down' ? '↓' : '' }}
          {{ Math.abs(trend) }}%
        </span>

        <h3 class="m-0 text-[1.75rem] font-semibold leading-tight text-gray-900 ml-auto">
          {{ valueFormatted }}
        </h3>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

// type Variants = 'default' | 'success' | 'warning' | 'danger' | string

interface Props {
  label: string
  value: any
  icon?: string | object
  trend?: number
  variant?: Variants
  isLoading?: boolean
  currency?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  isLoading: false,
  variant: 'default',
})

const iconDesign = computed(() => {
  const variants: Record<Variants, string> = {
    danger: 'bg-danger-soft/20 text-danger-soft-foreground',
    success: 'bg-success-soft/20 text-success-soft-foreground',
    warning: 'bg-warning-soft/20 text-warning-soft-foreground',
    primary: 'bg-primary-soft/20 text-primary-soft-foreground',
    info: 'bg-info-soft/20 text-info-soft-foreground',
    restore: 'bg-restore-soft/20 text-restore-soft-foreground',
    default: 'bg-muted text-foreground',
    text: '',
  }
  return variants[props.variant ?? 'default']
})

const valueFormatted = computed(() => {
  if (props.value === null) {
    return 'No data'
  }

  if (typeof props.value === 'number') {
    const options: Intl.NumberFormatOptions = {
      notation: 'compact',
      compactDisplay: 'short',
      maximumFractionDigits: 2,
    }

    if (props.currency) {
      options.style = 'currency'
      options.currency = 'PHP'
    }

    return props.value.toLocaleString('en-PH', options)
  }

  return props.value
})

const trendDirection = computed(() => {
  if (props.trend === undefined || props.trend === null) return null
  if (props.trend > 0) return 'up'
  if (props.trend < 0) return 'down'
  return 'neutral'
})

// left-border accent lang, hindi buong background
const variantClasses = computed(() => {
  const map: Record<string, string> = {
    success: 'border-l-[3px] border-l-emerald-600',
    warning: 'border-l-[3px] border-l-amber-600',
    danger: 'border-l-[3px] border-l-red-600',
  }
  return map[props.variant ?? 'default'] ?? ''
})

const trendClasses = computed(() => {
  const map: Record<string, string> = {
    up: 'text-emerald-600 bg-emerald-600/10',
    down: 'text-red-600 bg-red-600/10',
    neutral: 'text-gray-500 bg-gray-500/[.08]',
  }
  return trendDirection.value ? map[trendDirection.value] : ''
})
</script>

<style scoped>
@keyframes shimmer {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}
</style>
