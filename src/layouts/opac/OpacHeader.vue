<template>
  <!-- <header class="sticky top-0 overflow-hidden text-primary backdrop-blur-lg z-99">
    <section class="mx-auto flex w-full max-w-[100rem] gap-5 p-5">
      <div class="flex items-center gap-3 text-2xl font-bold">
        <Logo />
        <span>e-Libra</span>
      </div>
      <div class="ml-auto flex items-center gap-1">
        <Button as="link" variant="text" class="hidden rounded-full border border-primary/10 bg-primary/5 px-4 text-sm font-medium hover:bg-primary/10 sm:inline-flex">Ask a Librarian</Button>

        <template v-if="auth.isAuthenticated">
          <Button @click="user.goHome()" variant="text" class="hover:bg-background/10 transition-all duration-300" data-title="Home">
            <Icon icon="house-fill" class="text-xl" />
          </Button>
          <Button as="link" variant="text" class="hover:bg-background/10 transition-all duration-300" :data-title="auth.getFullName">
            <Icon icon="person-circle" class="text-xl" />
          </Button>
        </template>
        <Button as="link" variant="text" class="hover:bg-background/10 transition-all duration-300" data-title="Book Bag">
          <Icon icon="bag-fill" class="text-xl" />
        </Button>
      </div>
    </section>
  </header> -->

  <Header />
</template>

<script setup lang="ts">
import isu from '@/assets/images/isu.png'

const auth = authStore()
const user = useAuth()
</script>

<style scoped></style>
