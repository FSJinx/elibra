<template>
  <div class="inset-0 fixed flex bg-background h-dvh z-9999" v-if="auth.token && !auth.isAuthenticated">
    <div class="m-auto">
      <LogoLoader class="text-lg" message="Fetching info, please wait..." />
    </div>
  </div>

  <router-view v-else />
  <Error />
  <Tooltip />
</template>

<script setup lang="ts">
import Error from '@/app/error/Error.vue'

const auth = authStore()
</script>

<style scoped></style>
