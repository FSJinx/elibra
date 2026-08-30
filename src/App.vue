<template>
  <router-view />
  <Error />
  <Tooltip />
</template>

<script setup lang="ts">
import Error from '@/app/error/Error.vue'

const auth = authStore()
const authUse = useAuth()

// onMounted(async () => {
//   if (auth.token && !auth.isAuthenticated) {
//     await authUse.getUser()
//     console.log(auth.user)
//   }
// })
</script>

<style scoped></style>
