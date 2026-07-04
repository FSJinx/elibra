<template>
  <section class="h-[30vh] w-6xl mx-auto">
    <h1 class="font-bold text-primary text-2xl">Browse</h1>

    <div class="flex items-center">
      <button class="p-2 border rounded mr-5" @click="dec">-1</button>
      <template v-for="step in steps" :key="step">
        <span
          class="flex items-center justify-center h-13 w-13 rounded-full border-2 transition-all duration-300"
          :class="{
            'bg-primary text-white border-primary': step <= current,
            'border-primary text-primary': step > current,
          }"
        >
          {{ step }}
        </span>

        <div v-if="step !== steps.length" class="w-15 h-0.5 bg-gray-200">
          <div class="h-full bg-primary transition-all duration-500" :class="step < current ? 'w-full' : 'w-0'" />
        </div>
      </template>
      <button class="p-2 border rounded ml-5" @click="incr">+1</button>
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

const steps = [1, 2, 3, 4, 5]
const current = ref(1)

function incr() {
  if (current.value < steps.length) {
    current.value++
  }
}

function dec() {
  if (current.value > 1) {
    current.value--
  }
}
</script>
