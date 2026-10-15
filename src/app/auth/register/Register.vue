<template>
  <div class="bg-secondary">
    <Card class="max-h-80vh w-full max-w-200 mx-auto rounded-2xl! p-8 focus-within:shadow-2xl hover:shadow-2xl transition-shadow duration-150">
      <!-- Logo Header -->
      <div class="flex flex-col items-center text-center text-primary gap-1 mb-5">
        <router-link as="link" :to="{ name: 'home' }" variant="text" class="my-3">
          <Logo class="text-5xl" />
        </router-link>
        <h1 class="font-bold text-2xl">Welcome to e-Libra</h1>
        <p class="text-muted-foreground text-sm">Create an account and start enjoying patron-exclusive features</p>
      </div>

      <Alert v-if="errorMessage" variant="danger" class="mb-5">{{ errorMessage }}</Alert>
      <!-- Form -->
      <Form id="registration-form" @submit="submit" class="flex flex-col gap-5">
        <PersonalInformation :form="form" :errors="errors" />
        <AccountInformation :form="form" :errors="errors" />
        <Button form="registration-form" type="submit" variant="primary">Create</Button>
      </Form>
      <span class="text-sm text-center mt-5">Already have an account? <router-link :to="{ name: 'login', replace: true }" class="text-primary">Login</router-link></span>
    </Card>
  </div>
</template>

<script setup lang="ts">
import AccountInformation from '@/app/auth/register/sections/AccountInformation.vue'
import PersonalInformation from '@/app/auth/register/sections/PersonalInformation.vue'

const store = authStore()
const auth = useAuth()
const router = useRouter()
const pop = usePopup()

const emptyForm = (): Partial<User> => ({
  last_name: '',
  first_name: '',
  middle_initial: '',
  sex: '',
  campus_id: '',
  email: '',
  username: '',
  password: '',
  password_confirmation: '',

  patron_type_id: '',
})

const form = reactive(emptyForm())
const errors = reactive(emptyForm())
const errorMessage = ref()

async function submit() {
  const confirm = await pop.confirm({ text: 'Upon submission of your registration, it will be pending for approval. Are you sure you want to submit your registration now?' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await auth.register(form)
      if (res.status === 'success') {
        router.replace({ name: 'home' })
        pop.success(res.message ?? 'Register successful')
      }
    } catch (e: any) {
      const data = e.response.data
      console.log(data)
      errors.value = data.errors
    }
  }
}

watch([() => form.password_confirmation, () => form.password], () => {
  if (form.password != form.password_confirmation) {
    errors.password_confirmation = 'Passwords do not match, please re-check.'
  } else {
    errors.password_confirmation = ''
  }
})
</script>

<style scoped></style>
