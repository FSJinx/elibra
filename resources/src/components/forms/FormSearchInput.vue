<template>
  <div class="relative inline-flex items-center w-full border border-slate-300 overflow-hidden transition-all duration-200 focus-within:shadow-green-200 focus-within:shadow-[0_0_0_0.20rem]" :class="[getRadius(radius)]">
    <Transition name="search">
      <button class="absolute left-1 p-2 px-3" v-if="!isSearching">
        <Eicon icon="Search" class="text-slate-500"></Eicon>
      </button>
    </Transition>
    <input name="academic-query" id="academic-query" type="text" class="pr-10 w-full h-10 focus-within:outline-0 transition-all duration-200" :class="[!isSearching ? 'pl-10' : 'pl-5']" placeholder="Search by title, call number, researcher" v-model="model" @focus="(emit('focus'), (isSearching = true))" @blur="(emit('blur'), (isSearching = false))" autocomplete="off" />
    <button class="absolute right-1 p-2 cursor-pointer shrink-0 hover:bg-slate-100 transition-all duration-150" @click="clear" v-if="model.length > 0" :class="[getRadius(radius)]">
      <Eicon icon="X"></Eicon>
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Eicon from '../ui/Eicon.vue'
import { getRadius, type Radius } from '../../composables/useRadius.js'

const model = defineModel<string>({ default: '' })
const isSearching = ref<boolean>(false)

interface Props {
  radius?: Radius
}

const props = withDefaults(defineProps<Props>(), {
  radius: 'rounded',
})

const clear = () => {
  model.value = ''
}

const emit = defineEmits(['focus', 'blur'])
</script>

<style scoped>
.search-enter-active,
.search-leave-active {
  opacity: 1;
  transition: all 0.2s ease;
}

.search-enter-from,
.search-leave-to {
  opacity: 0;
  transform: translateX(-10px);
}
</style>
