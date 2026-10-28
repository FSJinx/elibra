<template>
  <main class="min-h-full bg-slate-50 px-4 pt-6 pb-24 text-slate-900 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl space-y-6">
      <Button
        as="link"
        :to="{ name: 'patron.dashboard' }"
        variant="text"
        size="sm"
        class="rounded-md border border-emerald-700 bg-transparent text-emerald-700 hover:bg-emerald-50"
      >
        Back to dashboard
      </Button>
      <header>
        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Academic repository</p>
        <h1 class="mt-2 text-3xl font-bold">Digital manuscript submission</h1>
        <p class="mt-3 text-slate-600">Submit your research details for library review. Your program will be taken from your patron profile.</p>
      </header>
      <Alert v-if="error" variant="danger">{{ error }}</Alert>
      <Alert v-if="submitted" variant="success" title="Manuscript submitted">
        <p class="mt-2">“{{ submitted.title }}” has been submitted and is pending review.</p>
        <div class="mt-4 flex flex-wrap items-center gap-3">
          <a
            v-if="submitted.electronic_file"
            :href="`${storageRoute}/${submitted.electronic_file}`"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex h-11 items-center rounded-md border border-emerald-700 bg-transparent px-3.5 text-sm font-medium text-emerald-700 transition-colors hover:bg-emerald-50"
          >
            Open submitted file
          </a>
          <Button type="button" variant="success" @click="submitted = null">Submit another manuscript</Button>
        </div>
      </Alert>
      <Form v-else class="grid gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2" @submit="submit">
        <Control id="manuscript-title" required col class="sm:col-span-2">
          <Label>Title</Label>
          <Input v-model="form.title" :max="255" placeholder="Enter manuscript title" :disabled="busy" />
        </Control>
        <Control id="manuscript-category" required col>
          <Label>Category</Label>
          <Select v-model="form.category" :disabled="busy">
            <Option value="ut">Undergraduate thesis</Option>
            <Option value="gt">Graduate thesis</Option>
          </Select>
        </Control>
        <Control id="manuscript-library" required col>
          <Label>Library</Label>
          <Select v-model="form.library_id" :disabled="loading || busy || !campusLibraries.length">
            <Option :value="null" disabled>Select your campus library</Option>
            <Option v-for="library in campusLibraries" :key="library.id" :value="library.id">{{ library.name }}</Option>
          </Select>
          <p v-if="!loading && !campusLibraries.length" class="text-sm text-danger">
            No libraries are configured for your campus.
          </p>
        </Control>
        <Control id="manuscript-language" required col>
          <Label>Language</Label>
          <Select v-model="form.language_id" :disabled="loading || busy">
            <Option :value="null" disabled>Select a language</Option>
            <Option v-for="language in options.languages" :key="language.id" :value="language.id">{{ language.name }}</Option>
          </Select>
        </Control>
        <Control id="manuscript-electronic-file" required col class="sm:col-span-2">
          <Label>Electronic File</Label>
          <input
            id="manuscript-electronic-file"
            ref="electronicFileInput"
            type="file"
            accept=".pdf,.doc,.docx"
            required
            :disabled="busy"
            class="block w-full rounded-md border border-slate-300 bg-white p-2 text-sm file:mr-3 file:rounded file:border-0 file:bg-emerald-50 file:px-3 file:py-1 file:text-emerald-700"
            @change="selectFile"
          />
          <p class="text-sm text-slate-500">PDF, DOC, or DOCX; maximum 20 MB.</p>
          <p v-if="electronicFile" class="text-sm text-slate-600">{{ electronicFile.name }}</p>
        </Control>
        <Control id="manuscript-abstract" required col class="sm:col-span-2">
          <Label>Abstract</Label>
          <Textarea v-model="form.abstract" :rows="8" placeholder="Enter your research abstract" :disabled="busy" />
        </Control>
        <Control id="manuscript-doi" nullable col class="sm:col-span-2">
          <Label>DOI</Label>
          <Input v-model="form.doi" :max="255" placeholder="Enter DOI" :disabled="busy" />
        </Control>
        <Control id="manuscript-keywords" required col class="sm:col-span-2">
          <Label>Keywords</Label>
          <div class="flex flex-col">
            <AddKeywords v-model="form" :disabled="busy" subtitle="Add keywords or subjects for your manuscript." />
            <div class="flex flex-wrap gap-2 mt-5">
              <p v-if="!form.keywords.length" class="text-center text-muted-foreground">No keywords yet.</p>
              <Chip v-for="tag in form.keywords.slice(0, 1)" :key="tag">{{ tag }}</Chip>
              <Chip v-if="form.keywords.length > 1" :title="form.keywords.slice(1).join(', ')">+{{ form.keywords.length - 1 }} more</Chip>
            </div>
          </div>
        </Control>
        <div class="sm:col-span-2 flex items-center gap-4">
          <Button type="submit" variant="success" :loading="busy" :disabled="busy || loading || !form.library_id || !form.language_id || !options.languages?.length || !electronicFile">{{ busy ? 'Submitting...' : 'Submit manuscript' }}</Button>
          <span v-if="loading" role="status" class="text-sm text-slate-500">Loading languages...</span>
          <Button v-else-if="!options.languages?.length || !options.libraries?.length" type="button" @click="loadLanguages">Retry options</Button>
        </div>
      </Form>
    </div>
  </main>
</template>

<script setup lang="ts">
import type { AcaRepo, AcaRepoPayload } from '@/interfaces/acarepo'
import AddKeywords from '@/app/librarian/collections/catalog/forms/sections/modals/AddKeywords.vue'
import { storageRoute } from '@/plugins/axios'

const store = acaRepoStore()
const options = optionStore()
const auth = authStore()
const defaults = (): AcaRepoPayload => ({
  title: '',
  category: 'ut',
  abstract: '',
  doi: null,
  keywords: [],

  status: 'pending',
  statusRemark: null,
  on_catalog: false,

  library_id: null,
  language_id: null,
  program_id: null,
})

const form = ref(defaults())
const electronicFile = ref<File | null>(null)
const electronicFileInput = ref<HTMLInputElement | null>(null)
const campusLibraries = computed(() => options.libraries?.filter((library) => String(library.campus_id) === String(auth.user?.campus_id)) ?? [])
const busy = ref(false)
const loading = ref(false)
const error = ref('')
const submitted = ref<AcaRepo | null>(null)

function failure(e: any) {
  const messages = e.response?.data?.errors
  error.value = (messages ? Object.values(messages).flat().join(' ') : e.response?.data?.message || 'Unable to complete the request. Please try again.').replace(/description/gi, 'abstract')
}

async function loadLanguages() {
  loading.value = true
  error.value = ''
  try {
    await Promise.all([options.fetch('languages'), options.fetch('libraries')])
  } catch (e) {
    failure(e)
  } finally {
    loading.value = false
  }
}

function selectFile(event: Event) {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0] ?? null
  if (file && file.size > 20 * 1024 * 1024) {
    electronicFile.value = null
    input.value = ''
    error.value = 'The electronic file must not exceed 20 MB.'
    return
  }
  electronicFile.value = file
}

async function submit() {
  if (busy.value) return
  error.value = ''
  if (!form.value.language_id) {
    error.value = 'Select a language.'
    return
  }
  if (!form.value.library_id) {
    error.value = 'Select a library from your campus.'
    return
  }
  const file = electronicFile.value
  if (!file) {
    error.value = 'Attach the electronic manuscript file.'
    return
  }

  const words = form.value.keywords.map((word) => word.trim()).filter(Boolean)
  if (!words.length) {
    error.value = 'Enter at least one keyword.'
    return
  }

  busy.value = true
  try {
    submitted.value = (await store.create({ ...form.value, doi: form.value.doi || null, keywords: words }, file)).data
    form.value = defaults()
    electronicFile.value = null
    if (electronicFileInput.value) electronicFileInput.value.value = ''
  } catch (e) {
    failure(e)
  } finally {
    busy.value = false
  }
}

onBeforeMount(loadLanguages)
</script>
