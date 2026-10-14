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

      <Alert v-if="error.message" variant="danger" class="mb-5">{{ error.message }}</Alert>
      <!-- Form -->
      <Form id="" @submit="" class="flex flex-col gap-5">
        <h5 class="uppercase font-medium text-muted-foreground">Personal Information</h5>
        <div class="grid grid-cols-5 gap-3">
          <Control id="form-last_name" class="col-span-3" col required>
            <Label>Last Name</Label>
            <Input type="text" placeholder="e.g. Dela Cruz" />
          </Control>
          <Control id="form-last_name" class="col-span-2" col nullable>
            <Label>Middle Initial</Label>
            <Input type="text" placeholder="e.g. A" />
          </Control>
          <Control id="form-first_name" class="col-span-5" col required>
            <Label>First Name</Label>
            <Input type="text" placeholder="e.g. Dela Cruz" auto-focus />
          </Control>
          <Control id="form-last_name" class="col-span-2" col>
            <Label>Sex</Label>
            <Select required>
              <Option value="" selected disabled>Select a sex</Option>
              <Option value="male">Male</Option>
              <Option value="female">Female</Option>
            </Select>
          </Control>
          <Control id="form-last_name" class="col-span-3" col>
            <Label>Campus</Label>
            <Select required>
              <Option value="">No campus (Patrons who do not belong to ISU)</Option>
              <Option :value="campus.id" v-for="campus in campuses.data">{{ campus.name }}</Option>
            </Select>
          </Control>
        </div>

        <h5 class="uppercase font-medium text-muted-foreground">Account Information</h5>
        <div class="grid grid-cols-5 gap-3">
          <Control id="form-first_name" class="col-span-3" col required>
            <Label>Email</Label>
            <Input type="text" placeholder="e.g. Dela Cruz" auto-focus />
          </Control>
          <Control id="form-last_name" class="col-span-2" col required>
            <Label>Username</Label>
            <Input type="text" placeholder="e.g. Dela Cruz" />
          </Control>
          <Control id="form-last_name" class="col-span-5" col required>
            <Label>Password</Label>
            <Input type="password" placeholder="Enter your password" />
          </Control>
          <Control id="form-last_name" class="col-span-5" col required>
            <Label>Confirm Password</Label>
            <Input type="password" placeholder="Confirm your password" />
          </Control>
        </div>
      </Form>
      <Button form="" type="submit" variant="primary">Create</Button>
      <span class="text-sm text-center mt-5">Already have an account? <router-link :to="{ name: 'login', replace: true }" class="text-primary">Login.</router-link></span>
    </Card>
  </div>
</template>

<script setup lang="ts">
const store = authStore()
const auth = useAuth()
const router = useRouter()
const campuses = useCampusStore()

const error = reactive({
  status: null,
  message: null,
  username: null,
  password: null,
})

const credentials = reactive({
  username: '',
  password: '',
})

async function handleSubmit() {
  Object.assign(error, {
    status: null,
    message: null,
    username: null,
    password: null,
  })

  const res = await auth.login({ username: credentials.username, password: credentials.password })

  console.log(res)

  if (!res.success) {
    Object.assign(error, {
      status: res.data?.status,
      message: res.data?.message ?? '',
      username: res.data?.data?.username ?? null,
      password: res.data?.data?.password ?? null,
    })
    console.log(error)
  } else {
    return router.push({ name: res.reroute })
  }
}
</script>

<style scoped></style>
