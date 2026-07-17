<template>
  <div class="flex relative h-screen">
    <Header />
    <div class="flex-1 max-w-6xl m-auto place-items-center">
      <slot />

      <div class="place-items-center space-x-5 p-10">
        <e-button icon-left="ArrowLeft" variant="outline-solid" color="success">Go Back</e-button>
        <e-link-button :to="{ name: path }" icon-left="House" variant="solid-hover" color="success">Home</e-link-button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import EButton from '../components/my/Button.vue/index.js'
import ELinkButton from '../components/my/LinkButton.vue/index.js'
import Header from './Header.vue'
import { authStore } from '../stores/auth.js'

const auth = authStore()
const user = computed(() => auth?.user)

const path = computed(() => {
  if (auth?.isAuthenticated) {
    if (user.value?.role === 'admin') {
      return 'admin'
    } else if (user.value?.role === 'librarian') {
      return 'librarian'
    }
  }

  return 'home'
})
</script>

<style scoped></style>
