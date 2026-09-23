<template>
  <div class="flex w-full flex-col gap-1.5">
    <div class="group relative flex w-full items-center bg-background border transition-all duration-150 min-w-20 rounded-md overflow-hidden focus-within:ring-4" :class="[sizeConfig.container, error && error.length > 0 ? 'border-danger focus-within:border-danger focus-within:ring-danger/20' : 'border-border focus-within:ring-success/25 focus-within:border-primary/50', { 'opacity-60 cursor-not-allowed bg-slate-100': disabled }]">
      <input ref="input" :id="id" :name="id" v-model="model" type="time" :placeholder="placeholder" :required="required || control?.required" :disabled="disabled" :readonly="readonly" :min="min" :max="max" :step="step" :autocomplete="autocomplete" class="h-full w-full flex-1 text-slate-900 transition-all duration-150 focus:outline-none disabled:cursor-not-allowed" :class="[sizeConfig.input, 'pl-2', hasSuffixActions ? 'pr-2' : 'pr-4']" @keydown="handleKeydown" />

      <div v-if="hasSuffixActions" class="flex items-center h-full shrink-0">
        <button v-if="enableClear && hasValue && !disabled && !readonly" type="button" class="flex h-full items-center justify-center px-3 text-slate-500 hover:text-slate-800 focus:outline-none border-l border-border transition-colors" aria-label="Clear time" @click="clear">
          <Icon icon="x" name="Clear Time" />
        </button>
      </div>
    </div>

    <div v-if="helper" class="flex items-center gap-1.5 text-xs text-info">
      <Icon icon="info-circle" />
      <span>{{ helper }}</span>
    </div>

    <p v-if="error && error.length > 0" class="text-xs font-medium text-danger">
      {{ error }}
    </p>

    <p v-if="warning && warning.length > 0" class="text-xs text-warning">
      {{ warning }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'

type Sizes = 'xs' | 'sm' | 'md' | 'lg' | 'xl'
type Autocomplete = 'on' | 'off' | string

interface Props {
  id: string
  placeholder?: string
  min?: string
  max?: string
  step?: number | string
  required?: boolean
  disabled?: boolean
  readonly?: boolean
  autoFocus?: boolean
  autocomplete?: Autocomplete
  enableClear?: boolean
  size?: Sizes
  error?: string | null
  helper?: string
  warning?: string
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: '',
  required: false,
  disabled: false,
  readonly: false,
  autoFocus: false,
  autocomplete: 'off',
  enableClear: false,
  size: 'md',
})

const model = defineModel<string>({ default: '' })
const input = ref<HTMLInputElement | null>(null)

const hasValue = computed(() => model.value.length > 0)
const hasSuffixActions = computed(() => props.enableClear && hasValue.value && !props.disabled && !props.readonly)
const control = inject<any>('control', null)

const sizeMap: Record<Sizes, { container: string; input: string }> = {
  xs: { container: 'h-8', input: 'text-xs' },
  sm: { container: 'h-10', input: 'text-sm' },
  md: { container: 'h-11', input: 'text-base' },
  lg: { container: 'h-14', input: 'text-lg' },
  xl: { container: 'h-18', input: 'text-xl' },
}

const sizeConfig = computed(() => sizeMap[props.size] ?? sizeMap.md)

function clear() {
  model.value = ''
  input.value?.focus()
}

function handleKeydown(event: KeyboardEvent) {
  if (event.key === 'Escape' && hasValue.value && props.enableClear) {
    clear()
  }
}

onMounted(() => {
  if (props.autoFocus) {
    input.value?.focus()
  }
})
</script>

<style scoped></style>
