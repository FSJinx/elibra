<template>
  <header class="sticky flex top-0 w-full z-40 text-primary border-b border-border/50 bg-background/80 backdrop-blur-md shadow">
    <nav class="mx-auto flex w-full max-w-[100rem] items-center justify-between py-3 px-5 sm:px-6">
      <router-link :to="{ name: 'home' }" class="flex min-w-0 items-center gap-2 text-primary text-xl">
        <Logo />
        <h1 class="font-black shrink-0">e-Libra</h1>
      </router-link>

      <Button icon="list" class="sm:hidden" @click="openNav = !openNav"></Button>

      <div class="sm:flex hidden items-center justify-end gap-2">
        <template v-if="!auth.isAuthenticated">
          <Button as="link" :to="{ name: 'login' }" class="hover:bg-primary/10 transition-all duration-300" variant="text">Login</Button>
          <Button as="link" :to="{ name: '' }" variant="text" class="hover:bg-primary/10 transition-all duration-300">Register</Button>
        </template>

        <template v-else>
          <Button as="link" :to="{ name: 'home' }" variant="text" class="hover:bg-primary/10 transition-all duration-300" data-title="Home" v-if="$route.name !== 'home'">Home</Button>
          <Button @click="user.goHome()" variant="text" class="hover:bg-primary/10 transition-all duration-300" data-title="Dashboard">Dashboard</Button>
          <Button variant="text" class="hover:bg-primary/10 transition-all duration-300" :data-title="auth.getFullName">Profile</Button>
          <Button @click="user.logout" variant="text" class="hover:bg-primary/10 transition-all duration-300" data-title="Logout">Logout</Button>
        </template>

        <div class="h-5 border-l mx-3"></div>

        <Button as="link" :to="{ name: 'opac' }" variant="text" class="hover:bg-primary/10 transition-all duration-300" data-title="Online Public Access Catalog" v-if="$route.name !== 'opac'">OPAC</Button>
        <Button variant="text" class="hover:bg-primary/10 transition-all duration-300" data-title="Book Bag">
          <Icon icon="bag-fill" class="text-xl" />
        </Button>
      </div>
    </nav>
  </header>

  <Transition name="fade">
    <div class="inset-0 fixed flex sm:hidden justify-end bg-backdrop" @click.self="openNav = !openNav" v-if="openNav">
      <div class="flex flex-col h-full w-80 px-5 py-3 bg-background" :class="[openNav ? 'm-0' : '-mr-80']">
        <!-- Side Navigation Header -->
        <div class="flex items-center gap-2 text-primary text-xl">
          <Logo />
          <h1 class="font-bold shrink-0">e-Libra</h1>
          <Button icon="x-lg" class="ml-auto" @click="openNav = false"></Button>
        </div>

        <!-- Side Navigation Body -->
        <div class="mt-5 flex flex-col">
          <Button as="link" :to="{ name: 'home' }" variant="text" class="hover:bg-primary/10 transition-all duration-300" data-title="Home" v-if="$route.name !== 'home'">Home</Button>
          <Button @click="user.goHome()" variant="text" class="hover:bg-primary/10 transition-all duration-300" data-title="Dashboard">Dashboard</Button>
          <Button variant="text" class="hover:bg-primary/10 transition-all duration-300" :data-title="auth.getFullName">Profile</Button>
          <Button @click="user.logout" variant="text" class="hover:bg-primary/10 transition-all duration-300" data-title="Logout">Logout</Button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
const auth = authStore()
const user = useAuth()
const openNav = ref<boolean>(false)

const quickLinks = ref({
  home: { label: 'Home', icon: 'House', path: '' },
  profile: { label: 'Profile', icon: 'UserCircle', path: '' },
  bag: { label: 'Book Bag', icon: 'ShoppingBag', path: '' },
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
