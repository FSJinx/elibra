<template>
  <h5 class="uppercase font-medium text-muted-foreground">Account Information</h5>
  <div class="grid grid-cols-5 gap-3">
    <Control id="email" class="col-span-3" col required>
      <Label>Email</Label>
      <Input type="text" placeholder="e.g. Dela Cruz" v-model="form.email" :error="errors.email" />
    </Control>
    <Control id="username" class="col-span-2" col required>
      <Label>Username</Label>
      <Input type="text" placeholder="e.g. Dela Cruz" v-model="form.username" :error="errors.username" />
    </Control>
    <Control id="password" class="col-span-5" col required>
      <Label>Password</Label>
      <Input type="password" placeholder="Enter your password" v-model="form.password" :error="errors.password" />
    </Control>
    <Control id="confirmPassword" class="col-span-5" col required>
      <Label>Confirm Password</Label>
      <Input input="confpassInput" type="password" placeholder="Confirm your password" v-model="form.password_confirmation" :error="errors.password_confirmation" />
    </Control>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  form: Partial<User>
  errors: Partial<User>
}>()
</script>
