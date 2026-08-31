<template>
  <div v-if="isVisible" class="flex items-start gap-3 border px-3.5 py-3 rounded-lg my-1 text-sm leading-normal" :class="alertClass" role="alert" aria-live="polite">
    <component :is="alertIcon" class="mt-0.5 size-5 shrink-0" aria-hidden="true" />

    <div class="min-w-0 flex-1">
      <p v-if="title" class="font-semibold leading-5">{{ title }}</p>
      <div :class="title && 'mt-0.5'">
        <slot />
      </div>
    </div>

    <button v-if="dismissible" type="button" class="-mr-1 -mt-1 inline-flex size-7 shrink-0 items-center justify-center rounded-md opacity-70 transition-opacity hover:opacity-100 focus-visible:outline-2 focus-visible:outline-offset-2" aria-label="Dismiss alert" @click="dismiss">
      <X class="size-4" aria-hidden="true" />
    </button>
  </div>
</template>

<script setup lang="ts">
import { Bell, CircleCheck, CircleX, Info, TriangleAlert, X } from '@lucide/vue'

interface Props {
  variant?: Variants
  title?: string
  dismissible?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'text',
  title: '',
  dismissible: false,
})

const emit = defineEmits<{
  close: []
}>()

const isVisible = ref(true)

const alertClass = computed(() => {
  const variants: Record<Variants, string> = {
    primary: 'bg-primary-soft text-primary-soft-foreground border-primary',
    default: 'bg-background text-foreground border-border',
    danger: 'bg-danger-soft/75 text-danger-soft-foreground border-danger',
    info: 'bg-info-soft text-info-soft-foreground border-info',
    restore: 'bg-restore-soft text-restore-soft-foreground border-restore',
    success: 'bg-success-soft text-success-soft-foreground border-success',
    text: 'bg-transparent text-muted border-transparent',
    warning: 'bg-warning-soft text-warning-soft-foreground border-warning',
  }

  return variants[props.variant]
})

const alertIcon = computed(() => {
  const icons: Record<Variants, any> = {
    primary: Bell,
    default: Info,
    danger: CircleX,
    info: Info,
    restore: Bell,
    success: CircleCheck,
    text: Info,
    warning: TriangleAlert,
  }

  return icons[props.variant]
})

function dismiss() {
  isVisible.value = false
  emit('close')
}
</script>

<style scoped></style>
