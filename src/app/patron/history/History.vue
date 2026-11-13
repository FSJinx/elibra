<template>
  <div class="flex flex-col size-full gap-5 max-w-6xl mx-auto py-5 px-5 lg:px-0">
    <Title :level="2">History</Title>

    <div class="flex flex-col gap-2">
      <router-link v-for="link in links" :to="{ name: link.path }" class="flex items-center bg-background border border-border rounded-lg p-5 hover:bg-primary active:bg-primary hover:text-primary-foreground active:text-primary-foreground transition duration-200 group">
        <Title>{{ link.label }}</Title>

        <Icon icon="chevron-right" class="opacity-0 group-hover:opacity-100 group-active:opacity-100 ml-auto transition duration-200" />
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
const links = [
  { label: 'Attendance', path: 'patron.attendance' },
  { label: 'Borrowing', path: 'patron.borrowing' },
  { label: 'Reservations', path: 'patron.reservation' },
]
</script>

<style scoped></style>
