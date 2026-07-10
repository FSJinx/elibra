<template>
  <input v-model="model" :type="type" :name="name" :id="id" :placeholder="placeholder" :autocomplete="autocomplete" :disabled="disabled" :required="required" class="inline-flex h-10 w-full px-4 transition placeholder:text-slate-400 disabled:cursor-not-allowed disabled:opacity-60" :class="inputClasses" />
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  id?: string
  name?: string
  type?: 'text' | 'email' | 'password' | 'search' | 'tel' | 'url' | 'number'
  placeholder?: string
  autocomplete?: string
  disabled?: boolean
  required?: boolean
  border?: boolean
  borderColor?: string
  radius?: 'rounded' | 'bubble' | 'circle'
}

const model = defineModel<string>({ default: '' })

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  placeholder: 'Input',
  border: true,
  borderColor: 'border-slate-300',
  radius: 'rounded',
})

const inputClasses = computed(() => {
  const radiusClasses = {
    rounded: 'rounded-md',
    bubble: 'rounded-2xl',
    circle: 'rounded-full',
  }

  const borderClasses = props.border ? 'border' : 'border border-transparent'

  return [radiusClasses[props.radius], borderClasses, props.borderColor]
})
</script>

<style scoped></style>
