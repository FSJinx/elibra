<template>
  <main class="min-h-full bg-slate-50 px-4 pt-6 pb-24 text-slate-900 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl space-y-6">
      <router-link :to="{ name: 'patron.dashboard' }" class="text-sm font-semibold text-emerald-700">Back to dashboard</router-link>
      <header>
        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Academic repository</p>
        <h1 class="mt-2 text-3xl font-bold">Digital manuscript submission</h1>
        <p class="mt-3 text-slate-600">Submit your research details for library review. Your program will be taken from your patron profile.</p>
      </header>
      <p v-if="error" role="alert" class="rounded-xl bg-red-50 p-4 text-red-700">{{ error }}</p>
      <section v-if="submitted" role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">
        <h2 class="text-xl font-bold text-emerald-800">Manuscript submitted</h2>
        <p class="mt-2">“{{ submitted.title }}” has been submitted and is pending review.</p>
        <Button type="button" variant="success" class="mt-4" @click="submitted = null">Submit another manuscript</Button>
      </section>
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
        <Control id="manuscript-language" required col>
          <Label>Language</Label>
          <Select v-model="form.language_id" :disabled="loading || busy">
            <Option :value="null" disabled>Select a language</Option>
            <Option v-for="language in options.languages" :key="language.id" :value="language.id">{{ language.name }}</Option>
          </Select>
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
          <Textarea v-model="keywords" :rows="3" helper="Enter one keyword per line." :disabled="busy" />
        </Control>
        <div class="sm:col-span-2 flex items-center gap-4">
          <Button type="submit" variant="success" :loading="busy" :disabled="busy || loading || !form.language_id || !options.languages?.length">{{ busy ? 'Submitting...' : 'Submit manuscript' }}</Button>
          <span v-if="loading" role="status" class="text-sm text-slate-500">Loading languages...</span>
          <Button v-else-if="!options.languages?.length" type="button" @click="loadLanguages">Retry languages</Button>
        </div>
      </Form>
    </div>
  </main>
</template>

<script setup lang="ts">
import { onBeforeMount, ref } from 'vue'
import { acaRepoStore, type AcaRepo, type AcaRepoPayload } from '@/stores/acarepo.store'
import { optionStore } from '@/stores/ui/option.store'

const store = acaRepoStore()
const options = optionStore()
const defaults = (): AcaRepoPayload => ({ title: '', category: 'ut', abstract: '', doi: null, keywords: [], status: 'pending', statusRemark: null, on_catalog: false, language_id: null, program_id: null })
const form = ref(defaults())
const keywords = ref('')
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
  try { await options.fetch('languages') } catch (e) { failure(e) } finally { loading.value = false }
}
async function submit() {
  if (busy.value) return
  error.value = ''
  if (!form.value.language_id) { error.value = 'Select a language.'; return }
  const words = keywords.value.split('\n').map(word => word.trim()).filter(Boolean)
  if (!words.length) { error.value = 'Enter at least one keyword.'; return }
  busy.value = true
  try {
    submitted.value = (await store.create({ ...form.value, doi: form.value.doi || null, keywords: words })).data
    form.value = defaults()
    keywords.value = ''
  } catch (e) { failure(e) } finally { busy.value = false }
}
onBeforeMount(loadLanguages)
</script>

