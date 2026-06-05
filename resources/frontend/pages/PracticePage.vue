<template>
  <div class="flex flex-col h-auto w-full overflow-y-auto">
    <!-- Buttons -->
    <ButtonPractice />

    <!-- Inputs -->
    <InputPractive />
  </div>
</template>

<script setup>
import ButtonPractice from '@/sections/practice/ButtonPractice.vue'
import InputPractive from '@/sections/practice/InputPractive.vue'
</script>

<style>
th,
td {
  padding: 1rem;
  border: 1px solid black;
}

th {
  background-color: gainsboro;
  color: black;
}
</style>
