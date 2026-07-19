<template>
  <Header />
  <section class="relative flex min-h-screen w-full items-center justify-center px-4 py-16">
    <div class="w-full max-w-4xl text-center">
      <div class="space-y-3">
        <h1 class="text-4xl font-extrabold text-primary">Library Services</h1>
        <p class="text-gray-500">These are the list of the library services offered by all of our campuses.</p>
      </div>
      <div class="mt-10 grid w-full grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <template v-for="route in routes">
          <router-link :to="{ name: route.path }" class="card p-5 text-left">
            <span class="badge border-green-500 bg-green-50 mb-5">
              <component :is="route.icon" class="h-5 w-5"></component>
            </span>

            <h1 class="font-bold text-xl mb-2">{{ route.label }}</h1>
            <p class="text-gray-500 leading-normal">{{ route.description }}</p>
          </router-link>
        </template>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import Header from '@/layouts/Headers.vue'
import { ref } from 'vue'

const routes = ref([
  { label: 'Collections', description: 'Browse our hundreds and thousands of collections in our OPAC.', icon: 'Library', path: 'OPAC' },
  { label: 'Equipments', description: 'Browse available library equipments that you may use.', icon: 'ToolCase', path: '' },
  { label: 'Subscriptions', description: 'Browse our collections of extended resources from our partner resources.', icon: 'GlobeCheck', path: '' },
])
</script>

<style scoped></style>
