<template>
  <div class="relative flex flex-col">
    <FeatureBelt />
    <div class="flex flex-col min-h-dvh">
      <Header />

      <main classs="flex-1">
        <slot />
      </main>
    </div>

    <Footer />
  </div>
</template>

<script setup lang="ts"></script>

<style scoped></style>
