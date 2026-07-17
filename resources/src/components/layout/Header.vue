<template>
  <header class="sticky top-0 z-40 border-b border-border/10 bg-background dark:bg-background backdrop-blur-md">
    <nav class="mx-auto flex h-17.5 w-full max-w-5xl items-center justify-between px-3 sm:px-6">
      <div class="flex min-w-0 items-center gap-2 text-teal-600">
        <Logo width="28" />
        <h1 class="font-black text-2xl font-poppins shrink-0">e-Libra</h1>
      </div>
      <div class="flex items-center gap-1.5 sm:gap-2">
        <ELinkButton to="login" color="success" variant="outline-solid">Login</ELinkButton>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
const auth = authStore()
const isAuthenticated = auth.isAuthenticated

const quickLinks = ref({
  home: { label: 'Home', icon: 'House', path: '' },
  profile: { label: 'Profile', icon: 'UserCircle', path: '' },
  bag: { label: 'Book Bag', icon: 'ShoppingBag', path: '' },
})
</script>
