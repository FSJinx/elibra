<template>
  <!-- Card Parent -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
    <!-- Card -->
    <div class="grid grid-cols-1 sm:text-end shrink-0 bg-container p-5 border border-border rounded-2xl hover:shadow-lg transition-all duration-300" v-for="card in datas" :data-title="card.data.toLocaleString() + ' ' + card.label.replace(/\b\w/g, (char) => char.toUpperCase())">
      <!-- Icon and Status -->
      <div class="flex items-start justify-between">
        <!-- Card Icon -->
        <span class="grid place-items-center size-13 text-lg rounded-full" :class="[`bg-${card.color}-soft text-${card.color}-soft-foreground`]">
          <Icon :icon="card.icon" />
        </span>

        <!-- Card Status -->
        <Badge class="ml-auto max-w-30 line-clamp-1 font-semibold" :title="card.status" :class="[`bg-${card.color}-soft text-${card.color}-soft-foreground`]" v-if="card.status">{{ card.status }}</Badge>
      </div>

      <!-- Data -->
      <span class="flex-1 text-end text-3xl font-medium">{{ card.data.toLocaleString() }}</span>

      <!-- Label -->
      <p class="text-end text-foreground-secondary tracking-tight text-sm capitalize">{{ card.label }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const datas = computed(() => {
  return [
    { icon: 'building', data: 8, status: '', label: 'Total active campus', color: 'info' },
    { icon: 'file-user', data: 11, status: '5%', label: 'Total library staff', color: 'success' },
    { icon: 'users', data: 1250, status: '12%', label: 'Total active students', color: 'warning' },
    { icon: 'book', data: 5420, status: '8%', label: 'Total books available', color: 'danger' },
    { icon: 'bookmark-check', data: 340, status: '2%', label: 'Books borrowed today', color: 'restore' },
  ]
})
</script>

<style scoped></style>
