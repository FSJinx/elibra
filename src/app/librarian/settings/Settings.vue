<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div v-if="isProfile" class="mx-auto max-w-4xl space-y-6">
      <section class="flex flex-col gap-5 rounded-2xl bg-primary p-6 text-primary-foreground sm:flex-row sm:items-center">
        <div class="grid size-20 shrink-0 place-items-center overflow-hidden rounded-2xl bg-white/10 text-2xl font-bold text-white">
          <img v-if="profileImageUrl && !profileImageFailed" :src="profileImageUrl" :alt="auth.getFullName" class="size-full object-cover" @error="profileImageFailed = true" />
          <span v-else>{{ auth.getInitials || 'EL' }}</span>
        </div>
        <div class="min-w-0">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-white-300">Staff profile</p>
          <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ auth.getFullName || 'Account profile' }}</h1>
          <p class="mt-1 text-sm text-white-100">{{ auth.displayRole || 'Staff' }}<span v-if="auth.user?.username"> · @{{ auth.user.username }}</span></p>
        </div>
        <Button class="sm:ml-auto" :disabled="loading" @click="loadProfile">
          {{ loading ? 'Refreshing...' : 'Refresh profile' }}
        </Button>
      </section>

      <Alert v-if="error" variant="danger">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <span>{{ error }}</span>
          <Button size="sm" :disabled="loading" @click="loadProfile">Try again</Button>
        </div>
      </Alert>

      <div v-if="loading && !auth.user" class="rounded-xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
        Loading your profile...
      </div>

      <template v-else-if="auth.user">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
              <h2 class="font-bold text-slate-900">Personal information</h2>
              <p class="mt-1 text-sm text-slate-500">Your name and contact details.</p>
            </div>
            <Button size="sm" :disabled="saving" @click="openEdit">Edit profile</Button>
          </div>
          <dl class="mt-6 grid gap-5 sm:grid-cols-2">
            <div><dt class="profile-label">First name</dt><dd class="profile-value">{{ auth.user.first_name || 'Not provided' }}</dd></div>
            <div><dt class="profile-label">Middle initial</dt><dd class="profile-value">{{ auth.user.middle_initial || 'Not provided' }}</dd></div>
            <div><dt class="profile-label">Last name</dt><dd class="profile-value">{{ auth.user.last_name || 'Not provided' }}</dd></div>
            <div><dt class="profile-label">Sex</dt><dd class="profile-value capitalize">{{ auth.user.sex || 'Not provided' }}</dd></div>
            <div><dt class="profile-label">Birth date</dt><dd class="profile-value">{{ formatDate(auth.user.birthdate) }}</dd></div>
            <div><dt class="profile-label">Contact number</dt><dd class="profile-value">{{ auth.user.contact_number || 'Not provided' }}</dd></div>
          </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
          <div>
            <h2 class="font-bold text-slate-900">Account information</h2>
            <p class="mt-1 text-sm text-slate-500">Your sign-in and account status details.</p>
          </div>
          <dl class="mt-6 grid gap-5 sm:grid-cols-2">
            <div>
              <dt class="profile-label">Email</dt>
              <dd class="profile-value">{{ auth.user.email || 'Not provided' }}</dd>
              <span v-if="auth.user.email_verified_at" class="mt-2 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Verified</span>
              <span v-else class="mt-2 inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Not verified</span>
            </div>
            <div><dt class="profile-label">Username</dt><dd class="profile-value">{{ auth.user.username || 'Not provided' }}</dd></div>
            <div><dt class="profile-label">Role</dt><dd class="profile-value">{{ auth.displayRole || 'Not provided' }}</dd></div>
            <div><dt class="profile-label">Account status</dt><dd class="profile-value capitalize">{{ auth.user.status || 'Not available' }}</dd></div>
          </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
          <div>
            <h2 class="font-bold text-slate-900">Organization</h2>
            <p class="mt-1 text-sm text-slate-500">Your campus and library assignment.</p>
          </div>
          <dl class="mt-6 grid gap-5 sm:grid-cols-2">
            <div><dt class="profile-label">User campus</dt><dd class="profile-value">{{ auth.user.campus?.name || 'Not assigned' }}</dd></div>
            <div><dt class="profile-label">Library</dt><dd class="profile-value">{{ auth.user.library?.name || 'Not assigned' }}</dd></div>
          </dl>
          <p v-if="auth.user.role === 'super_admin'" class="mt-5 rounded-lg bg-teal-50 p-3 text-sm text-teal-800">
            Super administrators have system-wide access and are not assigned to a specific library.
          </p>
        </section>

        <p class="px-1 text-xs text-slate-500">
          Account created: <span class="font-semibold text-slate-700">{{ formatDate(auth.user.created_at) }}</span>
          <span class="mx-2">·</span>
          Last updated: <span class="font-semibold text-slate-700">{{ formatDate(auth.user.updated_at) }}</span>
        </p>
      </template>
    </div>

    <div v-else class="mx-auto max-w-3xl space-y-6">
      <section class="rounded-2xl bg-teal-950 p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-300">Settings</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ title }}</h1>
        <p class="mt-2 text-sm text-teal-100">Tune the library workspace and account behavior.</p>
      </section>
      <section class="divide-y divide-slate-100 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <label v-for="setting in settings" :key="setting.name" class="flex items-center justify-between gap-5 py-5 first:pt-0 last:pb-0">
          <span>
            <strong class="block text-slate-800">{{ setting.name }}</strong>
            <small class="mt-1 block text-slate-500">{{ setting.detail }}</small>
          </span>
          <input v-if="setting.toggle" type="checkbox" :checked="setting.enabled" class="size-5 accent-teal-700" />
          <span v-else class="rounded-lg bg-teal-50 px-3 py-2 text-xs font-bold text-teal-700">{{ setting.value }}</span>
        </label>
      </section>
      <button type="button" class="rounded-lg bg-teal-700 px-4 py-3 text-sm font-semibold text-white">Save changes</button>
    </div>

    <Modal ref="editModal" size="large">
      <ModalHeader use-default-layout title="Edit personal information" subtitle="Update your staff profile details." icon="person" />
      <ModalBody>
        <form id="staff-profile-form" class="grid gap-4 p-5 sm:grid-cols-2" @submit.prevent="saveProfile">
          <div class="flex items-center gap-4 sm:col-span-2">
            <div class="grid size-16 shrink-0 place-items-center overflow-hidden rounded-full bg-primary/10 text-lg font-bold text-primary">
              <img v-if="selectedImagePreview || (profileImageUrl && !profileImageFailed)" :src="selectedImagePreview || profileImageUrl" alt="Profile picture preview" class="size-full object-cover" @error="profileImageFailed = true" />
              <span v-else>{{ auth.getInitials || 'EL' }}</span>
            </div>
            <label class="text-sm font-semibold text-slate-700">
              Profile picture
              <input class="mt-1 block w-full text-sm font-normal text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:font-semibold file:text-primary hover:file:bg-primary/20" type="file" accept="image/jpeg,image/png,image/webp" :disabled="saving" @change="selectProfilePicture" />
              <span class="mt-1 block text-xs font-normal text-slate-500">JPG, PNG, or WebP. Maximum file size: 5 MB.</span>
              <span v-if="fieldErrors.profile_picture" class="field-error">{{ fieldErrors.profile_picture }}</span>
            </label>
          </div>
          <label class="text-sm font-semibold text-slate-700">
            First name
            <input v-model.trim="form.first_name" required maxlength="255" class="field" :disabled="saving" />
            <span v-if="fieldErrors.first_name" class="field-error">{{ fieldErrors.first_name }}</span>
          </label>
          <label class="text-sm font-semibold text-slate-700">
            Last name
            <input v-model.trim="form.last_name" required maxlength="255" class="field" :disabled="saving" />
            <span v-if="fieldErrors.last_name" class="field-error">{{ fieldErrors.last_name }}</span>
          </label>
          <label class="text-sm font-semibold text-slate-700">
            Middle initial
            <input v-model.trim="form.middle_initial" maxlength="2" class="field" :disabled="saving" />
            <span v-if="fieldErrors.middle_initial" class="field-error">{{ fieldErrors.middle_initial }}</span>
          </label>
          <label class="text-sm font-semibold text-slate-700">
            Email
            <input v-model.trim="form.email" type="email" maxlength="255" class="field" :disabled="saving" />
            <span v-if="fieldErrors.email" class="field-error">{{ fieldErrors.email }}</span>
          </label>
          <label class="text-sm font-semibold text-slate-700">
            Contact number
            <input v-model.trim="form.contact_number" type="tel" inputmode="numeric" pattern="09[0-9]{9}" maxlength="11" placeholder="09XXXXXXXXX" class="field" :disabled="saving" />
            <span v-if="fieldErrors.contact_number" class="field-error">{{ fieldErrors.contact_number }}</span>
          </label>
          <label class="text-sm font-semibold text-slate-700">
            Birth date
            <input v-model="form.birthdate" type="date" class="field" :disabled="saving" />
            <span v-if="fieldErrors.birthdate" class="field-error">{{ fieldErrors.birthdate }}</span>
          </label>
          <label class="text-sm font-semibold text-slate-700">
            Sex
            <select v-model="form.sex" class="field" :disabled="saving">
              <option value="">Not specified</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
            <span v-if="fieldErrors.sex" class="field-error">{{ fieldErrors.sex }}</span>
          </label>
          <p v-if="saveError" class="text-sm text-danger sm:col-span-2">{{ saveError }}</p>
        </form>
      </ModalBody>
      <ModalFooter class="gap-2">
        <Button variant="danger" :disabled="saving" @click="closeEdit">Cancel</Button>
        <Button type="submit" form="staff-profile-form" :loading="saving">Save changes</Button>
      </ModalFooter>
    </Modal>
  </main>
</template>

<script setup lang="ts">
import api, { storageRoute } from '@/plugins/axios'
import Modal from '@/components/my/Modal.vue'

const route = useRoute()
const auth = authStore()
const loading = ref(false)
const error = ref('')
const editModal = ref<InstanceType<typeof Modal> | null>(null)
const saving = ref(false)
const saveError = ref('')
const fieldErrors = ref<Record<string, string>>({})
const profilePicture = ref<File | null>(null)
const selectedImagePreview = ref('')
const profileImageFailed = ref(false)
const form = reactive({
  first_name: '',
  last_name: '',
  middle_initial: '',
  email: '',
  contact_number: '',
  birthdate: '',
  sex: '',
})
const isProfile = computed(() => String(route.name).endsWith('.profile'))
const profileImageUrl = computed(() => {
  const picture = auth.user?.profile_picture ?? auth.user?.profilePicture
  const path = typeof picture === 'string' ? picture : picture?.file_path
  if (!path) return ''
  if (/^https?:\/\//i.test(path)) return path
  return `${storageRoute}/${path.replace(/^\/+/, '').replace(/^storage\//, '')}`
})
const title = computed(() => {
  const key = String(route.name).split('.').pop()
  return key === 'system' ? 'System Settings' : key === 'preferences' ? 'Preferences' : 'Account'
})

const settings = [
  { name: 'Default branch', detail: 'Used when opening circulation queues.', value: 'Main Library', toggle: false },
  { name: 'Daily digest', detail: 'Send a summary of desk activity each morning.', enabled: true, toggle: true },
  { name: 'Compact tables', detail: 'Fit more records into operational lists.', enabled: false, toggle: true },
  { name: 'Timezone', detail: 'Dates and reports use this local timezone.', value: 'Asia/Manila', toggle: false },
]

async function loadProfile() {
  loading.value = true
  error.value = ''
  try {
    const response = await api.get('auth')
    const user = response.data?.data as User | undefined
    if (!user) throw new Error('The server did not return your profile information.')
    profileImageFailed.value = false
    auth.setUser(user)
  } catch (e: any) {
    error.value = e?.response?.data?.message ?? e?.message ?? 'Unable to load your profile. Please try again.'
  } finally {
    loading.value = false
  }
}

function fillForm() {
  Object.assign(form, {
    first_name: auth.user?.first_name ?? '',
    last_name: auth.user?.last_name ?? '',
    middle_initial: auth.user?.middle_initial ?? '',
    email: auth.user?.email ?? '',
    contact_number: auth.user?.contact_number ?? '',
    birthdate: auth.user?.birthdate?.slice(0, 10) ?? '',
    sex: auth.user?.sex ?? '',
  })
}

function openEdit() {
  fillForm()
  saveError.value = ''
  fieldErrors.value = {}
  clearImagePreview()
  profilePicture.value = null
  editModal.value?.open()
}

function clearImagePreview() {
  if (selectedImagePreview.value) URL.revokeObjectURL(selectedImagePreview.value)
  selectedImagePreview.value = ''
}

function closeEdit() {
  clearImagePreview()
  profilePicture.value = null
  editModal.value?.close()
}

function selectProfilePicture(event: Event) {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0]
  if (!file) return

  if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
    fieldErrors.value.profile_picture = 'Choose a JPG, PNG, or WebP image.'
    input.value = ''
    return
  }
  if (file.size > 5 * 1024 * 1024) {
    fieldErrors.value.profile_picture = 'The profile picture must be 5 MB or smaller.'
    input.value = ''
    return
  }

  fieldErrors.value.profile_picture = ''
  clearImagePreview()
  profilePicture.value = file
  selectedImagePreview.value = URL.createObjectURL(file)
}

async function saveProfile() {
  saving.value = true
  saveError.value = ''
  fieldErrors.value = {}

  const payload = new FormData()
  payload.append('_method', 'PUT')
  payload.append('first_name', form.first_name)
  payload.append('last_name', form.last_name)
  if (form.middle_initial) payload.append('middle_initial', form.middle_initial)
  if (form.email) payload.append('email', form.email)
  if (form.contact_number) payload.append('contact_number', form.contact_number)
  if (form.birthdate) payload.append('birthdate', form.birthdate)
  if (form.sex) payload.append('sex', form.sex)
  if (profilePicture.value) payload.append('profile_picture', profilePicture.value)

  try {
    const response = await api.post('auth/me', payload)
    const user = response.data?.data as User | undefined
    if (!user) throw new Error('The server did not return updated profile information.')
    profileImageFailed.value = false
    auth.setUser(user)
    closeEdit()
    usePopup().success(response.data?.message ?? 'Profile updated successfully.')
  } catch (e: any) {
    const errors = e?.response?.data?.errors as Record<string, string[]> | undefined
    fieldErrors.value = Object.fromEntries(Object.entries(errors ?? {}).map(([key, messages]) => [key, messages[0]]))
    saveError.value = e?.response?.data?.message ?? e?.message ?? 'Unable to save your profile. Please try again.'
  } finally {
    saving.value = false
  }
}

function formatDate(value?: string | null) {
  if (!value) return 'Not available'
  const date = new Date(value)
  return Number.isNaN(date.getTime()) ? 'Not available' : new Intl.DateTimeFormat('en', { month: 'long', day: 'numeric', year: 'numeric' }).format(date)
}

onBeforeMount(() => {
  if (isProfile.value) loadProfile()
})

onBeforeUnmount(() => {
  clearImagePreview()
})
</script>

<style scoped>
.profile-label {
  color: #64748b;
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.025em;
  text-transform: uppercase;
}

.profile-value {
  margin-top: 0.25rem;
  color: #0f172a;
  font-weight: 600;
}

.field {
  width: 100%;
  margin-top: 0.25rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.5rem;
  background: white;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  outline: none;
}

.field:focus {
  border-color: #0f766e;
  box-shadow: 0 0 0 2px #ccfbf1;
}

.field:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}

.field-error {
  display: block;
  margin-top: 0.25rem;
  color: #dc2626;
  font-size: 0.75rem;
}
</style>
