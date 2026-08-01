<template>
  <div class="inset-0 fixed flex h-dvh" v-if="auth.token && !auth.isAuthenticated">
    <div class="m-auto">
      <LogoLoader class="text-lg" />
    </div>
  </div>
  <router-view class="" />
</template>

<script setup lang="ts">
const auth = authStore()
</script>

<style scoped></style>
