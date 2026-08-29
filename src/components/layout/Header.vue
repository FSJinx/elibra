<template>
  <header class="sticky flex top-0 z-40 text-primary border-b border-border/50 bg-background/80 backdrop-blur-md">
    <nav class="mx-auto flex w-full max-w-[100rem] items-center justify-between p-3 sm:px-6">
      <router-link :to="{ name: 'home' }" class="flex min-w-0 items-center gap-2 text-primary text-xl">
        <Logo />
        <h1 class="font-black shrink-0">e-Libra</h1>
      </router-link>
      <div class="flex items-center justify-end divide-x divide-primary">
        <div class="flex items-center gap-2 px-3 h-10">
          <template v-if="!auth.isAuthenticated">
            <Button as="link" :to="{ name: 'login' }" class="text-primary" variant="text">Login</Button>
            <Button as="link" :to="{ name: '' }" variant="primary">Register</Button>
          </template>

          <template v-else>
            <Button as="link" :to="{ name: 'home' }" variant="text" data-title="Home" v-if="$route.name !== 'home'">Home</Button>
            <Button @click="user.goHome()" variant="text" data-title="Dashboard">Dashboard</Button>
            <Button as="link" variant="text" :data-title="auth.getFullName">Profile</Button>
            <Button @click="user.logout" variant="text" data-title="Logout">Logout</Button>
          </template>
        </div>

        <div>
          <Button as="link" :to="{ name: 'opac' }" variant="text" data-title="OPAC" v-if="$route.name !== 'opac'">OPAC</Button>
          <Button as="link" variant="text" class="hover:bg-background/10 transition-all duration-300" data-title="Book Bag">
            <Icon icon="bag-fill" class="text-xl" />
          </Button>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
const auth = authStore()
const user = useAuth()

const quickLinks = ref({
  home: { label: 'Home', icon: 'House', path: '' },
  profile: { label: 'Profile', icon: 'UserCircle', path: '' },
  bag: { label: 'Book Bag', icon: 'ShoppingBag', path: '' },
})
</script>
