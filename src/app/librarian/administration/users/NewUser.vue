<template>
  <div class="flex flex-col gap-5 w-full max-w-5xl mx-auto p-5">
    <SectionHeader title="New User" description="Create a new user" icon="person" class="bg-background border border-border pb-5 rounded-xl"></SectionHeader>

    <Form id="create-user-form" class="flex flex-col gap-5" @submit="submit">
      <!-- Role Information -->
      <Card class="">
        <CardHeader> Role Information </CardHeader>

        <CardBody class="grid grid-cols-[1fr_2fr] items-start gap-5">
          <!-- Role -->
          <Label id="role" required>Role</Label>
          <Select id="role" required title="Roles" v-model="form.role" :error="errors.role?.[0]">
            <Option value="" selected disabled>Select role</Option>
            <Option value="admin">Admin</Option>
            <Option value="librarian">Librarian</Option>
            <Option value="patron">Patron</Option>
          </Select>

          <!-- Role Type -->
          <Label v-if="form.role == 'patron'" id="patron_type" required>Patron Type</Label>
          <Select v-if="form.role == 'patron'" id="patron_type" required title="Patron Types" v-model="form.patron_type_id" :error="errors.patron_type_id?.[0]">
            <Option value="" selected disabled>Select patron type</Option>
            <Option :value="type.id" v-for="type in options.patron_types">{{ type.name }}</Option>
          </Select>
        </CardBody>
      </Card>

      <Card class="">
        <CardHeader>
          Personal Information
          <p class="text-muted-foreground text-sm">Note. Password is set to the default password of the system (elibra2026)</p>
        </CardHeader>
        <CardBody class="grid grid-cols-[1fr_2fr] items-start gap-5">
          <Label id="last_name" required>Last Name</Label>
          <Input id="last_name" required type="text" placeholder="e.g. Dela Cruz" v-model="form.last_name" :error="errors.last_name?.[0]" />

          <Label id="first_name" required>First Name</Label>
          <Input id="first_name" required type="text" placeholder="e.g. Juan" v-model="form.first_name" :error="errors.first_name?.[0]" />

          <Label id="middle_initial" nullable>Middle Initial</Label>
          <Input id="middle_initial" nullable class="max-w-75" type="text" placeholder="e.g. A" max="2" v-model="form.middle_initial" :error="errors.middle_initial?.[0]" helper="Please put NA if no middle initial" />

          <Label id="sex" required>Sex</Label>
          <Select id="sex" class="max-w-75" required title="Sex" v-model="form.sex" :error="errors.sex?.[0]">
            <Option value="" selected disabled>Select sex</Option>
            <Option value="male">Male</Option>
            <Option value="female">Female</Option>
          </Select>

          <Label id="birthdate" required>Date of Birth</Label>
          <DatePicker id="birthdate" required v-model="form.birthdate" :error="errors.birthdate?.[0]" />

          <!-- Contact -->
          <Label id="contact_number" required>Contact Number</Label>
          <Input id="contact_number" type="tel" placeholder="e.g. 09xxxxxxxxx" max="11" required :error="errors.contact_number?.[0]" />

          <!-- Email -->
          <Label id="email" required>Email</Label>
          <Input id="email" type="text" placeholder="e.g. example@isu.edu.ph" required v-model="form.email" :error="errors.email?.[0]" />

          <!-- Username -->
          <Label id="username" required>Username</Label>
          <Input id="username" type="text" placeholder="e.g. juandelacruz" required v-model="form.username" :error="errors.username?.[0]" />
        </CardBody>
      </Card>

      <Card class="sticky bottom-0 flex-row justify-end gap-2!">
        <Button variant="danger" @click="router.back()">Cancel</Button>
        <Button form="create-user-form" type="submit" variant="success">Submit</Button>
      </Card>
    </Form>
  </div>
</template>

<script setup lang="ts">
const emptyForm = (): Partial<User> => ({
  role: '',
  patron_type_id: '',

  last_name: '',
  first_name: '',
  middle_initial: '',
  sex: '',
  birthdate: '',
  contact_number: '',
  email: '',
  username: '',
})

const pop = usePopup()
const form = reactive(emptyForm())
const errors = reactive(emptyForm())

const user = usersStore()
const options = optionStore()

async function submit () {
  const confirm = await pop.confirm({text: 'Create this user?'})

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await user.create(form)
      if (res.status === 'success') {
        pop.success(res.message)
        return router.back()
      }
    } catch (e: any) {
      
      throw e
    }
  }
}
</script>

<style scoped>
.card-body label {
  margin-top: 0.5rem;
}
</style>
