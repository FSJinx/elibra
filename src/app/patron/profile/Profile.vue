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

    <EditProfileModal ref="editModal" :user="auth.user" @saved="handleProfileSaved" />
  </main>
</template>

<script setup lang="ts">
import EditProfileModal from '@/app/patron/profile/modals/EditProfileModal.vue'

const auth = authStore()
const user = useAuth()
const pop = usePopup()
const editModal = ref<InstanceType<typeof EditProfileModal> | null>(null)
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
function openEditModal() { editModal.value?.open() }
function handleProfileSaved(user: User, message?: string) { auth.setUser(user); pop.fire({ title: 'Saved', text: message, icon: 'success' }) }
</script>

<style scoped>
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
