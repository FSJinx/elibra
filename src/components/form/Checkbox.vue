<template>
  <div class="flex w-full flex-col gap-1.5">
    <div class="flex cursor-pointer items-start gap-2 text-sm text-slate-700" :class="{ 'cursor-not-allowed opacity-60': disabled }">
      <input :id="inputId" :name="inputId" v-model="model" type="checkbox" :required="control?.required || required" :disabled="disabled" :tabindex="tabindex" class="mt-0.5 shrink-0 rounded border-border text-primary accent-primary transition disabled:cursor-not-allowed" :class="sizeConfig" />

      <label :for="inputId" class="mt-0.5 leading-5 select-none">
        <slot>{{ label }}</slot>
        <span v-if="(required || control?.required) && !$slots.default" class="text-danger">*</span>
      </label>
    </div>

    <div v-if="helper" class="flex items-center gap-1.5 text-sm text-info">
      <Icon icon="info-circle" />
      <span>{{ helper }}</span>
    </div>

    <p v-if="error" class="text-sm font-medium text-danger">{{ error }}</p>
    <p v-if="warning" class="text-sm text-warning">{{ warning }}</p>
  </div>
</template>

<script setup lang="ts">
type Sizes = 'sm' | 'md' | 'lg'

interface Props {
  id?: string
  label?: string
  required?: boolean
  disabled?: boolean
  tabindex?: number
  size?: Sizes
  helper?: string
  error?: string | null
  warning?: string
}

const props = withDefaults(defineProps<Props>(), {
  label: '',
  required: false,
  disabled: false,
  size: 'md',
})

const model = defineModel<boolean>({ default: false })
const control = inject<any>('control', null)

const inputId = computed(() => control?.id ?? props.id ?? `checkbox-${Math.random().toString(36).slice(2, 9)}`)

const sizeMap: Record<Sizes, string> = {
  sm: 'size-4',
  md: 'size-5',
  lg: 'size-6',
}

const sizeConfig = computed(() => sizeMap[props.size] ?? sizeMap.md)
</script>

<style scoped></style>
