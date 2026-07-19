<template>
  <div class="relative">
    <transition name="search">
      <span class="p-2 ml-1 absolute" v-if="!isSearching">
        <Search class="h-5 w-5 text-slate-500" />
      </span>
    </transition>
    <input ref="inputRef" v-model="model" type="text" id="query" name="query" :class="inputClass" placeholder="Search" autocomplete="off" @focus="handleFocus" @blur="emit('blur')" @input="emit('input')" />
    <span class="p-2 mr-1 absolute right-0 cursor-pointer" v-if="model" @click="clear">
      <X class="h-5 w-5 text-slate-500" />
    </span>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

const model = defineModel<string>({ default: '' })
const inputRef = ref<HTMLInputElement | null>(null)

const emit = defineEmits(['focus', 'blur', 'input'])
defineExpose({ close })

const props = withDefaults(
  defineProps<{
    isSearching?: boolean
  }>(),
  {
    isSearching: false,
  },
)

const inputClass = computed(() => {
  let def = 'w-md pr-4 py-2 bg-slate-200 border border-slate-300 rounded-full hover:border-primary transition-all duration-200'
  let c = []
  if (props.isSearching) {
    c.push('pl-4')
  } else {
    c.push('pl-9')
  }

  if (model.value.length > 0) {
    c.push('pr-9')
  } else {
    c.push('pr-4')
  }

  // return 'px-4'

  return [def, ...c]
})

const handleFocus = () => {
  if (model.value.length > 0) inputRef.value?.select()
  emit('focus')
}

const clear = () => (model.value = '')
</script>
<style scoped>
.search-enter-active,
.search-leave-active {
  transition: all 0.2s ease;
}

.search-enter-from,
.search-leave-to {
  opacity: 0;
  transform: translateX(-10px);
}

.search-enter-to,
.search-leave-from {
  opacity: 1;
  transform: translateX(0);
}
</style>
