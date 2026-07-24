<template>
  <div class="belt w-full py-2 bg-primary text-white text-xs overflow-hidden">
    <div class="belt-track flex w-max">
      <div class="belt-group gap-5 pr-5">
        <div v-for="(campus, index) in campuses" :key="`${campus}-${index}`" class="belt-item">
          <Logo width="10" />

          <span>{{ campus }}</span>
        </div>
      </div>

      <!-- Duplicate -->
      <div aria-hidden="true" class="belt-group gap-5 pr-5">
        <div v-for="(campus, index) in campuses" :key="`${campus}-duplicate-${index}`" class="belt-item">
          <Logo width="10" />

          <span>{{ campus }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const campuses = ref(['Echague Main Campus', 'Angadanan Campus', 'Cabagan Campus', 'Cauayan Campus', 'Ilagan Campus', 'Jones Campus', 'Roxas Campus', 'Santiago Campus', 'San Mariano Campus', 'San Mateo Campus'])
</script>

<style scoped>
.belt::-webkit-scrollbar {
  display: none;
}

.belt-track {
  animation: marquee 40s linear infinite;
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
