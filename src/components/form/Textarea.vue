<template>
  <div class="flex w-full flex-col gap-1.5">
    <!-- Textarea Container -->
    <div class="group relative flex w-full border transition-all duration-150 min-w-20 rounded-md overflow-hidden focus-within:ring-4" :class="[error && error.length > 0 ? 'border-danger focus-within:border-danger focus-within:ring-danger/20' : 'border-border focus-within:ring-success/25 focus-within:border-primary/50', { 'opacity-60 cursor-not-allowed bg-slate-100': disabled }]">
      <!-- Native Textarea -->
      <textarea ref="textarea" :id="id || control.id" :name="id" v-model="model" :placeholder="placeholder" :required="required" :disabled="disabled" :readonly="readonly" :maxlength="maxlength" :spellcheck="spellcheck" rows="1" @input="handleInput" class="w-full flex-1 bg-transparent text-slate-900 transition-all duration-150 read-only:border-transparent focus:outline-none disabled:cursor-not-allowed py-2.5 px-4" :class="[sizeConfig.input, resizeClass]" />

      <!-- Clear Button -->
      <button v-if="enableClear && hasValue && !disabled" type="button" class="absolute top-2 right-2 flex items-center justify-center h-6 w-6 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 focus:outline-none transition-colors" aria-label="Clear input" @click="clearInput">
        <Icon icon="x" name="Clear Input" />
      </button>
    </div>

    <!-- Helper Text + Char Count Row -->
    <div v-if="helper || maxlength" class="flex items-center justify-between gap-1.5 mt-1">
      <div v-if="helper" class="flex items-center gap-1.5 text-xs text-info">
        <Icon icon="info-circle" />
        <span>{{ helper }}</span>
      </div>
      <span v-if="maxlength" class="ml-auto text-xs" :class="charCount >= maxlength ? 'text-danger' : 'text-slate-400'"> {{ charCount }} / {{ maxlength }} </span>
    </div>

    <!-- Error Message -->
    <p v-if="error && error.length > 0" class="text-xs font-medium text-danger">
      {{ error }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick, onMounted, watch } from 'vue'

type Sizes = 'xs' | 'sm' | 'md' | 'lg' | 'xl'
type Resize = 'none' | 'vertical' | 'horizontal' | 'both'

interface Props {
  // Base
  id?: string
  placeholder?: string
  rows?: number

  // Validation
  required?: boolean
  maxlength?: number

  // Behavior
  disabled?: boolean
  readonly?: boolean
  autoFocus?: boolean
  spellcheck?: boolean
  resize?: Resize

  // Actions
  enableClear?: boolean

  // Display
  error?: string | null
  size?: Sizes
  helper?: string
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: '',
  rows: 4,
  required: false,
  disabled: false,
  readonly: false,
  autoFocus: false,
  spellcheck: true,
  resize: 'vertical',
  enableClear: false,
  size: 'md',
})

const model = defineModel<any>({ default: '' })
const textarea = ref<HTMLTextAreaElement | null>(null)
const charCount = ref(String(model.value ?? '').length)

const control = inject<any>('control')

// Safe value check
const hasValue = computed(() => String(model.value ?? '').length > 0)

// Font size configuration (no fixed height like Input — rows control that)
const sizeMap: Record<Sizes, { input: string }> = {
  xs: { input: 'text-xs' },
  sm: { input: 'text-sm' },
  md: { input: 'text-base' },
  lg: { input: 'text-lg' },
  xl: { input: 'text-xl' },
}

const sizeConfig = computed(() => sizeMap[props.size] ?? sizeMap.md)

const resizeMap: Record<Resize, string> = {
  none: 'resize-none',
  vertical: 'resize-y',
  horizontal: 'resize-x',
  both: 'resize',
}

const resizeClass = computed(() => resizeMap[props.resize] ?? resizeMap.vertical)

const adjustTextareaHeight = () => {
  const element = textarea.value
  if (!element) return

  element.style.height = 'auto'

  const styles = getComputedStyle(element)
  const lineHeight = parseFloat(styles.lineHeight)
  const verticalPadding = parseFloat(styles.paddingTop) + parseFloat(styles.paddingBottom)
  const maxRows = Math.min(Math.max(props.rows, 1), 4)
  const maxHeight = lineHeight * maxRows + verticalPadding

  element.style.maxHeight = `${maxHeight}px`
  element.style.height = `${Math.min(element.scrollHeight, maxHeight)}px`
  element.style.overflowY = element.scrollHeight > maxHeight ? 'auto' : 'hidden'
}

const handleInput = () => {
  charCount.value = String(model.value ?? '').length
  adjustTextareaHeight()
}

const clearInput = () => {
  model.value = ''
  charCount.value = 0
  adjustTextareaHeight()
  textarea.value?.focus()
}

watch(model, () => {
  nextTick(adjustTextareaHeight)
})

onMounted(() => {
  adjustTextareaHeight()

  if (props.autoFocus) {
    textarea.value?.focus()
  }
})
</script>
