<template>
  <div class="flex flex-col gap-5 w-full max-w-5xl mx-auto p-5">
    <SectionHeader title="New User" description="Create a new user" icon="person" class="bg-background border border-border pb-5 rounded-xl"></SectionHeader>

    <Form id="create-user-form" class="flex flex-col gap-5" @submit="submit">
      <!-- Role Information -->
      <Card class="">
        <CardHeader> Role Information </CardHeader>

        <CardBody class="flex flex-col gap-5">
          <!-- Role -->
          <Control id="role" required>
            <Label>Role</Label>
            <Select title="Roles" v-model="form.role" :error="errors.role?.[0]">
              <Option value="" selected disabled>Select role</Option>
              <Option value="admin">Admin</Option>
              <Option value="librarian">Librarian</Option>
              <Option value="patron">Patron</Option>
            </Select>
          </Control>

          <!-- Patron Type -->
          <Control id="patron_type" v-if="form.role == 'patron'" required>
            <Label>Patron Type</Label>
            <Select title="Patron Types" v-model="form.patron_type_id" :error="errors.patron_type_id?.[0]">
              <Option value="" selected disabled>Select patron type</Option>
              <Option :value="type.id" v-for="type in options.patron_types">{{ type.name }}</Option>
            </Select>
          </Control>

          <!-- Campus -->
          <Control id="campus" required v-if="form.role === 'admin' || form.role === 'librarian' || options.patron_types?.find((i) => i.id === form.patron_type_id)?.has_campus">
            <Label>Campus</Label>
            <Select id="campus" required title="Campuses" v-model="form.campus_id" :error="errors.campus_id?.[0]">
              <Option value="" selected disabled>Select campus</Option>
              <Option :value="campus.id" :label="campus.name" v-for="campus in options.campuses"></Option>
            </Select>
          </Control>

          <!-- Library -->
          <Control id="library" required v-if="form.role === 'admin' || form.role === 'librarian'">
            <Label>Library</Label>
            <Select title="Libraries" v-model="form.library_id" :error="errors.library_id?.[0]">
              <Option value="" selected disabled>Select library</Option>
              <Option :value="library.id" :label="library.name" v-for="library in options.libraries?.filter((i) => i.campus_id === form.campus_id)"></Option>
            </Select>
          </Control>
        </CardBody>
      </Card>

      <Card class="">
        <CardHeader>
          Personal Information
          <p class="text-muted-foreground text-sm">Note. Password is set to the default password of the system (elibra2026)</p>
        </CardHeader>
        <CardBody class="flex flex-col gap-5">
          <Control id="last_name" required>
            <Label>Last Name</Label>
            <Input type="text" placeholder="e.g. Dela Cruz" v-model="form.last_name" :error="errors.last_name?.[0]" />
          </Control>

          <Control id="first_name" required>
            <Label>First Name</Label>
            <Input type="text" placeholder="e.g. Juan" v-model="form.first_name" :error="errors.first_name?.[0]" />
          </Control>

          <Control id="middle_initial" nullable>
            <Label>Middle Initial</Label>
            <Input class="max-w-75" type="text" placeholder="e.g. A" max="2" v-model="form.middle_initial" :error="errors.middle_initial?.[0]" helper="Leave blank if no middle initial" />
          </Control>

          <Control id="sex" required>
            <Label>Sex</Label>
            <Select title="Sex" v-model="form.sex" :error="errors.sex?.[0]">
              <Option value="" selected disabled>Select sex</Option>
              <Option value="male">Male</Option>
              <Option value="female">Female</Option>
            </Select>
          </Control>

          <Control id="birthdate" required>
            <Label>Date of Birth</Label>
            <DatePicker v-model="form.birthdate" :error="errors.birthdate?.[0]" />
          </Control>

          <Control id="contact_number" nullable>
            <Label>Contact Number</Label>
            <Input type="tel" placeholder="e.g. 09xxxxxxxxx" max="11" v-model="form.contact_number" :error="errors.contact_number?.[0]" />
          </Control>

          <Control id="email" required>
            <Label>Email</Label>
            <Input type="email" placeholder="e.g. example@isu.edu.ph" v-model="form.email" :error="errors.email?.[0]" />
          </Control>

          <Control id="username" required>
            <Label>Username</Label>
            <Input type="text" placeholder="e.g. juandelacruz" v-model="form.username" :error="errors.username?.[0]" helper="For patron with ID Number, please use ID Number as username" />
          </Control>
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
  campus_id: '',
  library_id: null,

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

const user = userStore()
const options = optionStore()

async function submit() {
  const confirm = await pop.confirm({ text: 'Create this user?' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await user.create(form)
      if (res.status === 'success') {
        pop.success(res.message)
        return router.back()
      }
    } catch (e: any) {
      pop.error(e.response?.data?.message)
      throw e
    }
  }
}

onMounted(() => options.load(['campuses', 'libraries', 'patron_types']))
</script>

<style scoped>
.card-body label {
  margin-top: 0.5rem;
}
</style>
