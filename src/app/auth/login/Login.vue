<template>
  <div class="flex flex-col w-full max-w-120 p-8 mx-auto sm:bg-background rounded-4xl sm:shadow-lg hover:shadow-2xl transition-all duration-200">
    <!-- Logo Header -->
    <div class="flex flex-col items-center text-primary gap-1 mb-5">
      <Button as="link" :to="{ name: 'home' }" class="mx-auto">
        <Logo width="65" />
      </Button>
      <h1 class="font-bold text-2xl">Welcome Back!</h1>
      <p class="text-muted text-sm">Login now and continue where we left off.</p>
    </div>

    <Alert v-if="error" variant="danger">{{ error }}</Alert>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="flex flex-col space-y-3">
      <Input id="username" label="Username" label-position="top" type="username" v-model="credentials.username" required :disabled="loading"/>
      <Input id="password" label="Password" label-position="top" type="password" v-model="credentials.password" required :disabled="loading"/>

      <Button as="link" :to="{ name: '' }" size="small" variant="text" class="ml-auto text-primary">Forgot password?</Button>
      <Button variant="primary" type="submit" :loading="loading">Login</Button>
    </form>

    <span class="text-sm text-center mt-5">Don't have an account yet? <router-link :to="{ name: '' }" class="text-primary">Create an account.</router-link></span>
  </div>
</template>

<script setup lang="ts">
const { login, loading } = useAuth()

const error = ref('')

const credentials = ref({
  username: '',
  password: '',
})

async function handleSubmit() {
  const res = await login({ username: credentials.value.username, password: credentials.value.password })

  if (!res.success) {
    error.value = res.message
  } else {
  }
}
</script>

<style scoped></style>
