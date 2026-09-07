<template>
  <div class="flex w-full flex-col gap-1.5">
    <!-- Selected Files List -->
    <div v-if="fileList.length > 0" class="flex flex-col gap-2 mt-1">
      <div v-for="(file, index) in fileList" :key="`${file.name}-${index}`" class="flex items-center gap-3 px-3 py-2 border border-border rounded-md bg-background">
        <span class="flex items-center justify-center shrink-0 text-slate-400">
          <Icon icon="file-earmark" />
        </span>

        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-slate-900 truncate">{{ file.name }}</p>
          <p class="text-xs text-slate-500">{{ formatFileSize(file.size) }}</p>
        </div>

        <button type="button" class="shrink-0 text-slate-500 hover:text-danger focus:outline-none transition-colors" aria-label="Remove file" :disabled="disabled" @click.stop="removeFile(index)">
          <Icon icon="x" />
        </button>
      </div>
    </div>

    <!-- Dropzone Container -->
    <div
      v-else
      class="group relative flex w-full flex-col items-center justify-center border-2 border-dashed transition-all duration-150 rounded-md overflow-hidden cursor-pointer"
      :class="[sizeConfig.container, isDragging ? 'border-primary bg-primary/5' : hasError ? 'border-danger' : 'border-border hover:border-primary/50', { 'opacity-60 cursor-not-allowed bg-slate-100': disabled }]"
      @click="!disabled && triggerBrowse()"
      @dragover.prevent="!disabled && (isDragging = true)"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="handleDrop"
    >
      <!-- Hidden Native Input -->
      <input ref="input" :id="id" :name="id" type="file" class="hidden" :accept="accept" :multiple="multiple" :required="required" :disabled="disabled" @change="handleChange" />

      <span class="flex items-center justify-center shrink-0 text-slate-400 mb-1.5">
        <Icon :icon="leftIcon || 'cloud-upload'" :class="sizeConfig.icon" />
      </span>

      <p class="text-sm text-slate-600">
        <span class="font-medium text-primary">{{ placeholder || 'Click to upload' }}</span>
        <span v-if="!disabled"> or drag and drop</span>
      </p>

      <p v-if="hint" class="mt-0.5 text-xs text-slate-400">{{ hint }}</p>
    </div>

    <!-- Helper Text -->
    <div v-if="helper" class="flex items-center gap-1.5 text-xs text-info">
      <Icon icon="info-circle" />
      <span>{{ helper }}</span>
    </div>

    <!-- Error Message -->
    <p v-if="hasError" class="text-xs font-medium text-danger">
      {{ internalError || error }}
    </p>

    <!-- Warning Message -->
    <p v-if="warning && warning.length > 0" class="text-xs text-warning">
      {{ warning }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

type Sizes = 'xs' | 'sm' | 'md' | 'lg' | 'xl'

interface Props {
  // Base
  id: string
  placeholder?: string
  hint?: string
  accept?: string

  // Validation
  required?: boolean
  multiple?: boolean
  maxSize?: number // in bytes
  maxFiles?: number

  // Behavior
  disabled?: boolean

  // Display
  leftIcon?: string
  size?: Sizes
  error?: string
  helper?: string
  warning?: string
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: '',
  accept: '',
  required: false,
  multiple: false,
  disabled: false,
  size: 'md',
})

const model = defineModel<File[] | File | null>({ default: null })

const input = ref<HTMLInputElement | null>(null)
const isDragging = ref(false)
const internalError = ref('')

// Normalize model into a flat array for internal rendering, regardless of single/multiple mode
const fileList = computed<File[]>(() => {
  if (!model.value) return []
  return Array.isArray(model.value) ? model.value : [model.value]
})

const hasError = computed(() => internalError.value.length > 0 || (!!props.error && props.error.length > 0))

const sizeMap: Record<Sizes, { container: string; icon: string }> = {
  xs: { container: 'py-4', icon: 'text-lg' },
  sm: { container: 'py-6', icon: 'text-xl' },
  md: { container: 'py-8', icon: 'text-2xl' },
  lg: { container: 'py-10', icon: 'text-3xl' },
  xl: { container: 'py-12', icon: 'text-4xl' },
}

const sizeConfig = computed(() => sizeMap[props.size] ?? sizeMap.md)

function triggerBrowse() {
  input.value?.click()
}

function formatFileSize(bytes: number) {
  if (bytes < 1024) return `${bytes} B`
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`
  return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
}

function validateAndAssign(incoming: File[]) {
  internalError.value = ''

  const existing = fileList.value
  const combined = props.multiple ? [...existing, ...incoming] : incoming

  if (props.maxFiles && combined.length > props.maxFiles) {
    internalError.value = `You can only upload up to ${props.maxFiles} file${props.maxFiles > 1 ? 's' : ''}.`
    return
  }

  if (props.maxSize) {
    const oversized = incoming.find((file) => file.size > props.maxSize!)
    if (oversized) {
      internalError.value = `"${oversized.name}" exceeds the ${formatFileSize(props.maxSize)} size limit.`
      return
    }
  }

  model.value = props.multiple ? combined : (combined[0] ?? null)
}

function handleChange(e: Event) {
  const target = e.target as HTMLInputElement
  if (!target.files || target.files.length === 0) return

  validateAndAssign(Array.from(target.files))
  target.value = '' // reset so selecting the same file again still fires @change
}

function handleDrop(e: DragEvent) {
  isDragging.value = false
  if (props.disabled || !e.dataTransfer?.files) return

  validateAndAssign(Array.from(e.dataTransfer.files))
}

function removeFile(index: number) {
  if (!props.multiple) {
    model.value = null
    return
  }

  const updated = [...fileList.value]
  updated.splice(index, 1)
  model.value = updated
}
</script>
