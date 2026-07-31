<template>
  <AuthLayout>
    <!-- Logo Header -->
    <div class="flex flex-col items-center text-primary text-center gap-1 mb-10">
      <Button as="link" :to="{ name: 'home' }" class="mx-auto">
        <Logo width="65" />
      </Button>
      <h1 class="font-bold text-2xl">Create an account</h1>
      <p class="text-muted text-sm">Create an account and start enjoying exclusive features of e-Libra.</p>
    </div>

    <!-- Form -->
    <LoginForm />

    <span class="text-sm text-center mt-5">Don't have an account yet? <router-link :to="{ name: '' }" class="text-primary">Create an account.</router-link></span>
  </AuthLayout>
</template>

<script setup lang="ts">
const auth = useAuth()
const credentials = ref({
  username: '',
  password: '',
})
const login = () => {
  auth.login({ username: credentials.value.username, password: credentials.value.password })
}
</script>

<style scoped></style>
