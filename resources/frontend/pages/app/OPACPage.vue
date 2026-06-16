<template>
  <div class="min-h-100vh max-w-6xl mx-auto">
    <Card class="p-5">
      <div class="mb-10">
        <h1 class="font-bold text-2xl mb-1">Online Public Access Catalog (OPAC)</h1>
        <p class="text-gray-500 font-light">Browse our collection of books across multiple campuses and multiple categories.</p>
      </div>

      <form class="flex gap-2 items-center">
        <div class="relative flex items-center w-full">
          <Search class="absolute ml-3 text-gray-500 h-5 w-5" />
          <input type="text" name="query" class="w-full pl-10 p-2.5 bg-gray-50 rounded border border-gray-200" placeholder="Search here..." v-model="query" ref="queryInput" />
          <X class="absolute right-0 mr-3 text-gray-500 h-5 w-5 cursor-pointer" v-if="query" @click="clear" />
        </div>
        <Button icon="Search" label="Search" color="primary" />
        <Button icon="Filter" label="Filter" color="primary" variant="outline" />
      </form>
    </Card>
  </div>
</template>

<script setup>
import BaseInput from '@/components/BaseInput.vue'
import Button from '@/components/buttons/Button.vue'
import Card from '@/components/Card.vue'
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const queryInput = ref()
const query = ref(route.query.q || '')

function search() {
  // alert('User searched for: ' + query.value)
}

function clear() {
  query.value = ''
  queryInput.value?.focus()
}

onMounted(() => {
  if (query.value) {
    search()
  }
})
</script>

<style scoped></style>
