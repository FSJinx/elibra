<template>
  <MainLayout>
    <div class="place-content-center place-items-center space-y-5 w-full max-w-5xl min-h-[80dvh] m-auto text-center p-5">
      <div class="relative flex justify-center items-center h-25 w-25 bg-red-50 text-danger rounded-full">
        <Logo width="50" />
      </div>

      <div class="relative flex justify-center items-center text-xl p-2 font-extrabold tracking-wide text-danger uppercase">
        error
        <slot name="code" />

        <div class="h-0.5 w-[50%] min-w-10 absolute bottom-0 bg-danger rounded-full"></div>
      </div>

      <h1 class="text-4xl font-bold capitalize">
        <slot name="header" />
      </h1>

      <p class="text-lg text-center text-slate-600">
        <slot name="message" />
      </p>

      <div class="place-items-center space-x-5 p-10">
        <Button left-icon="ArrowLeft" variant="text" color="success" @click="router.back()">Go Back</Button>
        <Button as="link" :to="{ name: path }" left-icon="House" variant="primary" color="success">Home</Button>
      </div>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue'

const auth = authStore()
const user = computed(() => auth?.user)

console.log(router)

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
