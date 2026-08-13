<template>
  <header class="sticky flex top-0 z-40 border-b border-border/50 bg-background/80 backdrop-blur-md">
    <nav class="mx-auto flex w-full max-w-5xl items-center justify-between p-3 sm:px-6">
      <router-link :to="{ name: 'home' }" class="flex min-w-0 items-center gap-2 text-primary text-xl">
        <Logo />
        <h1 class="font-black shrink-0">e-Libra</h1>
      </router-link>
      <div class="flex items-center justify-end gap-1.5 sm:gap-3">
        <template v-if="!store.isAuthenticated">
          <Button as="link" :to="{ name: 'login' }" class="text-primary" variant="text">Login</Button>
          <Button as="link" :to="{ name: '' }" variant="primary">Register</Button>
        </template>
        <template v-else>
          <Button @click="auth.goHome()" variant="text" left-icon="home" class="text-primary">Home</Button>
        </template>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
const auth = useAuth()
const store = authStore()

const quickLinks = ref({
  home: { label: 'Home', icon: 'House', path: '' },
  profile: { label: 'Profile', icon: 'UserCircle', path: '' },
  bag: { label: 'Book Bag', icon: 'ShoppingBag', path: '' },
})
</script>
