<template>
  <div class="flex flex-col w-full p-15">
    <h1 class="text-4xl font-extrabold mb-15 text-center text-primary">What's New?</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
      <div v-for="feature in features" :key="feature.title" class="flex flex-col shadow-primary/15 p-8 rounded-lg shadow-md gap-4">
        <div class="rounded flex items-center justify-center bg-primary w-15 h-15 text-white">
          <component :is="feature.icon" class="w-8 h-8" />
        </div>

        <h3 class="text-xl font-bold">
          {{ feature.title }}
        </h3>

        <p class="text-neutral-500">
          {{ feature.description }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const features = ref([
  {
    title: 'Smart Search Capability',
    description: 'Find books quickly using keywords, titles, or categories in just a few clicks.',
    icon: 'Search',
  },
  {
    title: 'Access Library Resources',
    description: 'Browse available books and resources anytime without going to the library.',
    icon: 'Building2',
  },
  {
    title: 'Digital Borrower Card',
    description: 'Use your digital library card to borrow books and access services easily.',
    icon: 'IdCard',
  },
  {
    title: 'Thesis Submission',
    description: 'Upload and submit your thesis easily through the system.',
    icon: 'BookOpenCheck',
  },
])
</script>
