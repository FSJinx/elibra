<template>
  <div class="inset-0 fixed place-content-center bg-secondary">
    <div class="flex flex-col w-full max-w-120 p-8 mx-auto sm:bg-background border border-border/50 rounded-2xl sm:drop-shadow-sm hover:drop-shadow-xl transition-all duration-200">
      <!-- Logo Header -->
      <div class="flex flex-col items-center text-primary gap-1 mb-5">
        <Button as="link" :to="{ name: 'home' }" variant="text" class="mx-auto text-4xl mt-1">
          <Logo />
        </Button>
        <h1 class="font-bold text-2xl">Welcome Back!</h1>
        <p class="text-muted text-sm">Login now and continue where we left off</p>
      </div>
      <Alert v-if="error.message && error.message.length > 0" variant="danger" class="mb-5">{{ error.message }}</Alert>
      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="flex flex-col space-y-2">
        <!-- Username -->
        <Control direction="col">
          <Label required id="username">Username</Label>
          <Input id="username" type="username" placeholder="Username / Email / ID Number" autocomplete="password" v-model="credentials.username" required :disabled="store.loading" :error="error?.username ?? ''" />
        </Control>

        <!-- Password -->
        <Control direction="col">
          <Label required id="password">Password</Label>
          <Input id="password" type="password" placeholder="Enter Password" v-model="credentials.password" required :disabled="store.loading" :error="error?.password ?? ''" checkcapslock />
        </Control>

        <Button as="link" :to="{ name: '' }" size="small" variant="text" class="ml-auto text-primary">Forgot password?</Button>
        <Button variant="primary" type="submit" :loading="store.loading">Login</Button>
      </form>
      <span class="text-sm text-center mt-5">Don't have an account yet? <router-link :to="{ name: '' }" class="text-primary">Create an account.</router-link></span>
    </div>
  </div>
</template>

<script setup lang="ts">
const store = authStore()
const auth = useAuth()

const error = ref({
  status: '',
  message: '',
  username: '',
  password: '',
})

const credentials = ref({
  username: '',
  password: '',
})

async function handleSubmit() {
  error.value = {
    status: '',
    message: '',
    username: '',
    password: '',
  }

  const res = await auth.login({ username: credentials.value.username, password: credentials.value.password })

  console.log(res)

  if (!res.success) {
    error.value = {
      status: res.data?.status,
      message: res.data?.message,
      username: res.data?.data?.username,
      password: res.data?.data?.password,
    }
  }
}
</script>

<style scoped></style>
