<template>
  <div class="inset-0 fixed flex h-dvh" v-if="auth.token && !auth.isAuthenticated">
    <div class="m-auto">
      <LogoLoader class="text-lg" />
    </div>
  </div>
  <router-view class="" />
  <Error />
</template>

<script setup lang="ts">
import Error from '@/app/error/Error.vue'

const auth = authStore()
</script>

<style scoped></style>
