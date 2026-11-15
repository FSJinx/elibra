<template>
  <main class="px-5 pb-24 pt-8 sm:px-8 sm:pb-12">
    <div class="mx-auto flex max-w-4xl flex-col gap-5">
      <Card>
        <CardBody class="flex flex-col gap-5 sm:flex-row sm:items-start">
          <div class="grid size-20 shrink-0 place-items-center rounded-full bg-emerald-100 text-2xl font-bold text-emerald-800">{{ auth.getInitials || 'MS' }}</div>
          <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">My profile</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-950">{{ auth.getFullName || 'Member profile' }}</h1>
            <p class="mt-1 text-sm text-slate-500">
              {{ auth.user?.patron?.ebc_number }} · {{ auth.user?.patron?.patron_type?.name || 'Patron' }}<span v-if="auth.user?.campus?.name"> · {{ auth.user.campus.name }}</span>
            </p>
          </div>
          <Status class="ml-auto" :variant="auth.user?.status">{{ auth.user?.status }}</Status>
        </CardBody>
      </Card>

      <Card>
        <CardHeader class="flex items-start justify-between">
          <div>
            <Title>Basic Information</Title>
            <p class="text-sm text-foreground-secondary">Your personal and contact details.</p>
          </div>
          <Button @click="openEditModal">Edit</Button>
        </CardHeader>
        <CardBody class="grid gap-5 sm:grid-cols-2">
          <Info label="Last Name" :value="auth?.user?.last_name" />
          <Info label="First Name" :value="auth?.user?.first_name" />
          <Info label="Middle Initial" :value="auth?.user?.middle_initial" />
          <Info label="Birthday" :value="parse.formatDate(auth?.user?.birthdate)" />
          <Info label="Contact Number" :value="auth?.user?.contact_number" />
        </CardBody>
      </Card>

      <Card>
        <CardHeader>
          <Title>Account Information</Title>
          <p class="text-sm text-foreground-secondary">Your library account and sign-in details.</p>
        </CardHeader>
        <CardBody class="grid gap-5 sm:grid-cols-2">
          <Info label="Email" :value="auth?.user?.email"></Info>
          <Info label="Username / ID Number" :value="auth?.user?.username" />
          <Info label="Account Status">
            <Status :variant="auth.user?.status">{{ auth.user?.status }}</Status>
          </Info>
          <Info label="Email Status">
            <Status :variant="auth.user?.email_verified_at ? 'success' : 'warning'">{{ auth.user?.email_verified_at ? 'Verified' : 'Not yet verified' }}</Status>
          </Info>
          <Info class="col-span-2" label="Campus" :value="auth.user?.campus?.name" />
        </CardBody>
      </Card>

      <Card>
        <CardHeader>
          <Title>Membership Information</Title>
          <p class="text-sm text-foreground-secondary">Library membership and academic affiliation.</p>
        </CardHeader>
        <CardBody class="grid gap-5 sm:grid-cols-2">
          <Info label="Role" :value="parse.toCapital(auth?.user?.role)" />
          <Info label="Patron Type" :value="auth?.user?.patron?.patron_type?.name" />
          <Info label="EBC Number" :value="auth.user?.patron?.ebc_number" />
          <Info label="Account Valid Until" :value="parse.formatDate(auth.user?.patron?.account_expiry)" />
          <Info class="capitalize" label="Program" :value="formatProgram(auth.user?.patron?.program?.name)" />
          <Info label="Program Code" :value="auth.user?.patron?.program?.code?.toUpperCase()" />
        </CardBody>
      </Card>

      <Card>
        <div>
          <Title>Sign Out</Title>
          <p class="mt-1 text-sm text-muted-foreground">Securely sign out this account to this device</p>
        </div>
        <Button variant="danger" @click="user.logout()">Logout</Button>
      </Card>

      <div class="grid gap-3 px-1 text-xs text-slate-500 sm:grid-cols-2">
        <p>
          Account Created: <span class="font-semibold text-slate-700">{{ parse.formatDateTime(auth.user?.created_at) }}</span>
        </p>
        <p class="sm:text-right">
          Last Modified: <span class="font-semibold text-slate-700">{{ parse.formatDateTime(auth.user?.updated_at) }}</span>
        </p>
      </div>
    </div>

    <Modal ref="editModal" size="large">
      <ModalHeader use-default-layout title="Edit personal information" subtitle="Update your personal and contact details." icon="person" />
      <ModalBody class="p-5">
        <Form class="grid sm:grid-cols-2 gap-4" id="patron-profile-form" @submit="saveProfile">
          <Control id="user-last_name" col required>
            <Label>Last Name</Label>
            <Input type="text" placeholder="Enter last name" v-model="form.last_name" />
          </Control>
          <Control id="user-first_name" col required>
            <Label>First Name</Label>
            <Input type="text" placeholder="Enter first name" v-model="form.first_name" />
          </Control>
          <Control id="user-middle_initial" col nullable>
            <Label>Middle Initial</Label>
            <Input type="text" placeholder="Enter middle initial" v-model="form.middle_initial" />
          </Control>
          <Control id="user-sex" col required>
            <Label>Sex</Label>
            <Select v-model="form.sex" title="Sex">
              <Option value="male">Male</Option>
              <Option value="female">Female</Option>
            </Select>
          </Control>
          <Control id="user-email" col required>
            <Label>Email</Label>
            <Input type="email" placeholder="Enter email" v-model="form.email" />
          </Control>
          <Control id="user-birthdate" col required>
            <Label>Birthday</Label>
            <DatePicker v-model="form.birthdate" />
          </Control>
          <Control class="col-span-2" id="user-contact_number" col required>
            <Label>Contact Number</Label>
            <Input type="tel" max="11" min="11" placeholder="Enter contact number" v-model="form.contact_number" helper="Please enter contact number in 09xx format" />
          </Control>
        </Form>
      </ModalBody>
      <ModalFooter class="gap-2">
        <Button variant="danger" :disabled="saving" @click="editModal?.close()">Cancel</Button>
        <Button type="submit" form="patron-profile-form" variant="success" :loading="saving">Save changes</Button>
      </ModalFooter>
    </Modal>
  </main>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const auth = authStore()
const user = useAuth()
const pop = usePopup()
const parse = useParser()

const editModal = ref<InstanceType<typeof Modal> | null>(null)
const saving = ref(false)
const form = reactive({ first_name: '', last_name: '', middle_initial: '', email: '', contact_number: '', birthdate: '', sex: '' })

function formatProgram(name?: string | null) {
  if (!name) return 'Not assigned'
  const lowerCaseWords = new Set(['and', 'in', 'of', 'the'])
  return name
    .toLowerCase()
    .split(' ')
    .map((word, index) => (index > 0 && lowerCaseWords.has(word) ? word : `${word.charAt(0).toUpperCase()}${word.slice(1)}`))
    .join(' ')
}
function fillForm() {
  Object.assign(form, { first_name: auth.user?.first_name || '', last_name: auth.user?.last_name || '', middle_initial: auth.user?.middle_initial || '', email: auth.user?.email || '', contact_number: auth.user?.contact_number || '', birthdate: auth.user?.birthdate || '', sex: auth.user?.sex || '' })
}
watch(() => auth.user, fillForm, { immediate: true })
function openEditModal() {
  fillForm()
  editModal.value?.open()
}
async function saveProfile() {
  saving.value = true
  try {
    const response = await api.put('auth/profile', form)
    auth.setUser(response.data.data)
    editModal.value?.close()
    pop.fire({ title: 'Saved', text: response.data.message, icon: 'success' })
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.field {
  width: 100%;
  margin-top: 0.25rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  background: white;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  outline: none;
}

.field:focus {
  border-color: #059669;
  box-shadow: 0 0 0 2px #d1fae5;
}

.label {
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.025em;
  text-transform: uppercase;
  color: #94a3b8;
}

.value {
  margin-top: 0.25rem;
  font-weight: 600;
  color: #1e293b;
}
</style>
