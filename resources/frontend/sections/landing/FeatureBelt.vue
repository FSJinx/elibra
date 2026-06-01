<template>
  <div class="belt w-full py-3 bg-primary text-white overflow-hidden">
    <div class="belt-track flex w-max">
      <div class="belt-group gap-5 pr-5">
        <div v-for="(feature, index) in features" :key="`${feature}-${index}`" class="belt-item">
          <img :src="images.logo_white" alt="Logo" class="w-4 h-4 object-contain" />

          <span>{{ feature }}</span>
        </div>
      </div>

      <!-- Duplicate -->
      <div aria-hidden="true" class="belt-group gap-5 pr-5">
        <div v-for="(feature, index) in features" :key="`${feature}-duplicate-${index}`" class="belt-item">
          <img :src="images.logo_white" alt="Logo" class="w-4 h-4 object-contain" />

          <span>{{ feature }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import images from '@/assets/images'

const features = ref(['Access Digital Resources', 'Access Library Anytime & Anywhere', 'Smart Online Public Access Catalog (OPAC)', 'Digital Borrower Card', 'Online Thesis Submission', 'Real-Time Book Availability', 'Mobile-Friendly Access', 'University-Wide Library Integration'])
</script>

<style scoped>
.belt::-webkit-scrollbar {
  display: none;
}

.belt-track {
  animation: marquee 20s linear infinite;
}

.belt-group {
  display: flex;
}

.belt-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  white-space: nowrap;
  flex: 0 0 auto;
}

@keyframes marquee {
  from {
    transform: translateX(0);
  }

  to {
    transform: translateX(-50%);
  }
}
</style>
