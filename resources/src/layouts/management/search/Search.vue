<template>
  <div class="relative inline-flex">
    <SearchInput v-model="query" @focus="isSearching = true" @blur="isSearching = false" :isSearching="isSearching" />
    <SearchBody :show="show" :query="query" />
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import SearchInput from './SearchInput.vue'
import SearchBody from './SearchBody.vue'

const query = defineModel<string>({ default: '' })
const isSearching = ref(false)

const show = computed(() => {
  return isSearching.value && query.value.length > 0
})
</script>

<style scoped></style>
