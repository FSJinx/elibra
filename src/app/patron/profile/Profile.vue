<template>
  <main class="px-5 pb-24 pt-8 sm:px-8 sm:pb-12">
    <div class="mx-auto flex max-w-3xl flex-col gap-5">
      <Card>
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
          <div class="grid size-20 shrink-0 place-items-center rounded-2xl bg-emerald-100 text-2xl font-bold text-emerald-800">{{ auth.getInitials || 'MS' }}</div>
          <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">My profile</p><h1 class="mt-2 text-2xl font-bold text-slate-950">{{ auth.getFullName || 'Member profile' }}</h1><p class="mt-1 text-sm text-slate-500">{{ auth.user?.patron?.patron_type?.name || 'Patron' }}<span v-if="auth.user?.campus?.name"> · {{ auth.user.campus.name }}</span></p></div>
          <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 sm:ml-auto">{{ auth.user?.status || 'Active' }}</span>
        </div>
      </Card>

      <Card>
        <div class="flex items-start justify-between gap-4"><div><h2 class="font-bold text-slate-950">Basic information</h2><p class="mt-1 text-sm text-slate-500">Your personal and contact details.</p></div><Button @click="openEditModal">Edit</Button></div>
        <div class="mt-6 grid gap-5 sm:grid-cols-2">
          <div><p class="label">First name</p><p class="value">{{ auth.user?.first_name || 'Not provided' }}</p></div>
          <div><p class="label">Middle Initial</p><p class="value">{{ auth.user?.middle_initial || 'Not provided' }}</p></div>
          <div><p class="label">Last name</p><p class="value">{{ auth.user?.last_name || 'Not provided' }}</p></div>
          <div><p class="label">Sex</p><p class="value capitalize">{{ auth.user?.sex || 'Not provided' }}</p></div>
          <div><p class="label">Birth date</p><p class="value">{{ formatDate(auth.user?.birthdate) }}</p></div>
          <div><p class="label">Contact number</p><p class="value">{{ auth.user?.contact_number || 'Not provided' }}</p></div>
        </div>
      </Card>

      <Card>
        <div><h2 class="font-bold text-slate-950">Account information</h2><p class="mt-1 text-sm text-slate-500">Your library account and sign-in details.</p></div>
        <div class="mt-6 grid gap-5 sm:grid-cols-2">
          <div><p class="label">Email</p><div class="mt-1 flex flex-wrap items-center gap-2"><p class="value mt-0">{{ auth.user?.email || 'Not provided' }}</p><span v-if="!auth.user?.email_verified_at" class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">Not yet verified</span><span v-else class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Verified</span></div></div>
          <div><p class="label">Account status</p><Status class="value" :variant="auth.user?.status">{{ auth.user?.status || 'Not available' }}</Status></div>
          <div><p class="label">Campus</p><p class="value">{{ auth.user?.campus?.name || 'Not assigned' }}</p></div>
        </div>
      </Card>

      <Card>
        <div><h2 class="font-bold text-slate-950">Membership information</h2><p class="mt-1 text-sm text-slate-500">Library membership and academic affiliation.</p></div>
        <div class="mt-6 grid gap-5 sm:grid-cols-2">
          <div><p class="label">Patron type</p><p class="value">{{ auth.user?.patron?.patron_type?.name || 'Not assigned' }}</p></div>
          <div><p class="label">Program</p><p class="value">{{ formatProgram(auth.user?.patron?.program?.name) }}</p></div>
          <div><p class="label">Program code</p><p class="value uppercase">{{ auth.user?.patron?.program?.code || 'Not assigned' }}</p></div>
          <div><p class="label">Date joined</p><p class="value">{{ memberSince }}</p></div>
        </div>
      </Card>

      <Card><div><Title :level="4">Sign Out</Title><p class="text-sm text-muted-foreground">Securely sign out this account to this device</p></div><Button variant="danger" @click="user.logout()">Logout</Button></Card>

      <div class="grid gap-3 px-1 text-xs text-slate-500 sm:grid-cols-2">
        <p>Account created: <span class="font-semibold text-slate-700">{{ formatDateTime(auth.user?.created_at) }}</span></p>
        <p class="sm:text-right">Last modified: <span class="font-semibold text-slate-700">{{ formatDateTime(auth.user?.updated_at) }}</span></p>
      </div>
    </div>

    <Modal ref="editModal" size="large">
      <ModalHeader use-default-layout title="Edit personal information" subtitle="Update your personal and contact details." icon="person" />
      <ModalBody>
        <form id="patron-profile-form" class="grid gap-4 p-5 sm:grid-cols-2" @submit.prevent="saveProfile">
          <label class="text-sm font-semibold text-slate-700">First name<input v-model.trim="form.first_name" required class="field" /></label>
          <label class="text-sm font-semibold text-slate-700">Last name<input v-model.trim="form.last_name" required class="field" /></label>
          <label class="text-sm font-semibold text-slate-700">Middle initial<input v-model.trim="form.middle_initial" maxlength="2" class="field" /></label>
          <label class="text-sm font-semibold text-slate-700">Email<input v-model.trim="form.email" type="email" class="field" /></label>
          <label class="text-sm font-semibold text-slate-700">Contact number<input v-model.trim="form.contact_number" type="tel" maxlength="20" class="field" /></label>
          <label class="text-sm font-semibold text-slate-700">Birth date<input v-model="form.birthdate" type="date" class="field" /></label>
          <label class="text-sm font-semibold text-slate-700">Sex<select v-model="form.sex" class="field"><option value="">Prefer not to say</option><option value="male">Male</option><option value="female">Female</option></select></label>
        </form>
      </ModalBody>
      <ModalFooter class="gap-2"><Button variant="danger" :disabled="saving" @click="editModal?.close()">Cancel</Button><Button type="submit" form="patron-profile-form" :disabled="saving">{{ saving ? 'Saving...' : 'Save changes' }}</Button></ModalFooter>
    </Modal>
  </main>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const auth = authStore()
const user = useAuth()
const pop = usePopup()
const editModal = ref<InstanceType<typeof Modal> | null>(null)
const saving = ref(false)
const form = reactive({ first_name: '', last_name: '', middle_initial: '', email: '', contact_number: '', birthdate: '', sex: '' })
const memberSince = computed(() => formatDate(auth.user?.patron?.date_joined || auth.user?.created_at))
function formatDate(date?: string | null) { return date ? new Intl.DateTimeFormat('en', { month: 'long', day: 'numeric', year: 'numeric' }).format(new Date(date)) : 'Not provided' }
function formatDateTime(date?: string | null) { return date ? new Intl.DateTimeFormat('en', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }).format(new Date(date)) : 'Not available' }
function formatProgram(name?: string | null) {
  if (!name) return 'Not assigned'
  const lowerCaseWords = new Set(['and', 'in', 'of', 'the'])
  return name
    .toLowerCase()
    .split(' ')
    .map((word, index) => (index > 0 && lowerCaseWords.has(word) ? word : `${word.charAt(0).toUpperCase()}${word.slice(1)}`))
    .join(' ')
}
function fillForm() { Object.assign(form, { first_name: auth.user?.first_name || '', last_name: auth.user?.last_name || '', middle_initial: auth.user?.middle_initial || '', email: auth.user?.email || '', contact_number: auth.user?.contact_number || '', birthdate: auth.user?.birthdate || '', sex: auth.user?.sex || '' }) }
watch(() => auth.user, fillForm, { immediate: true })
function openEditModal() { fillForm(); editModal.value?.open() }
async function saveProfile() { saving.value = true; try { const response = await api.put('auth/profile', form); auth.setUser(response.data.data); editModal.value?.close(); pop.fire({ title: 'Saved', text: response.data.message, icon: 'success' }) } finally { saving.value = false } }
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
