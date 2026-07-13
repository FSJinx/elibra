<template>
  <div class="form-input w-full" :class="[positions.parent[labelPosition]]" @focusin="active = true" @focusout="active = false" :title="required ? (label ? label + ' is required.' : 'Required field.') : label + ' Input'">
    <label :for="id" class="text-md shrink-0 transition-all duration-150" :class="[positions.label[labelPosition], { 'label-floating': (active || model.length > 0) && labelPosition === 'float' }]" v-if="label">
      <span>{{ label }}</span>
      <span class="text-red-500" v-if="required"> *</span>
    </label>
    <div class="relative flex shrink min-w-50 items-center w-full border h-10 pl-4 overflow-hidden hover:shadow-[0_0_0_.20rem] hover:shadow-green-200 focus-within:shadow-[0_0_0_.20rem] focus-within:shadow-green-200" :class="[variants[variant]['text'], radi[radius]]">
      <input :type="type === 'password' ? (show ? 'text' : type) : type" :id="id" :name="id" class="pr-4 w-full h-full transition-all duration-150 focus:outline-none" v-model="model" :autocomplete="autocomplete" :placeholder="inputPlaceholder" :required="required" />
      <EiconButton variant="default-hover" :icon="show ? 'Eye' : 'EyeClosed'" :name="show ? 'Hide Password' : 'Show Password'" @click="show = !show" v-if="type === 'password'" />
      <EiconButton variant="default-hover" icon="X" name="Clear Input" @click="model = ''" v-if="enableClear && model.length > 0" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { radi, type Radius } from '../../composables/useRadius'
import { variants, type Variants } from '../../composables/useVariants'
import EiconButton from '../ui/EiconButton.vue'

type Types = 'text' | 'number' | 'password' | 'email' | 'tel' | 'hidden'
type Autocomplete = 'on' | 'off' | string
type LabelPosition = 'float' | 'default' | 'top'

interface Props {
  id: string
  label?: string
  type?: Types

  // Designs
  variant?: Variants
  radius?: Radius
  autocomplete?: Autocomplete
  placeholder?: string

  labelPosition?: LabelPosition
  required?: true | false
  enableClear?: true | false
}

const model = defineModel<string>({ default: '' })
const active = ref(false)
const show = ref(false)

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  variant: 'outline-hover',
  radius: 'rounded',
  autocomplete: 'off',
  labelPosition: 'default',
  required: false,
  enableClear: false,
})

const positions: Record<string, Record<LabelPosition, string>> = {
  parent: {
    float: `relative inline-flex items-center ${props.label ? 'pt-5' : ''}`,
    default: 'inline-flex items-center gap-5',
    top: 'inline-flex flex-col gap-2.5',
  },

  label: {
    float: 'absolute label-float left-0 pl-4 z-1 text-slate-500',
    default: 'w-35 line-clamp-1',
    top: '',
  },
}

const inputPlaceholder = computed(() => {
  if (props.labelPosition === 'float') {
    if (active.value) {
      return props.placeholder ?? props.label
    } else {
      return !props.label ? props.placeholder : ''
    }
  } else {
    return props.placeholder ?? props.label
  }
})
</script>

<style scoped>
.label-floating {
  transform: translate(-0.5em, -2.6em);
  font-size: var(--text-sm);
  transition: 0.25s;
}
.form-input:focus-within label {
  font-weight: 600;
}

.form-input:focus-within label,
.form-input:hover label {
  color: var(--color-secondary);
}
</style>
