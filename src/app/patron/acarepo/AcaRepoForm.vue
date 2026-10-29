<template>
  <main class="min-h-full bg-tertiary px-4 pt-6 pb-24 text-slate-900 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl space-y-6">
      <Button @click="router.back()" size="sm" variant="primary" left-icon="arrow-left"> Back </Button>
      <SectionHeader class="p-0!" title="Academic Repository" description="Submit your research details for library review. Your program will be taken from your patron profile." icon=""></SectionHeader>
      <!-- <header>
        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Academic repository</p>
        <h1 class="mt-2 text-3xl font-bold">Digital Manuscript Submission</h1>
        <p class="mt-3 text-slate-600">Submit your research details for library review. Your program will be taken from your patron profile.</p>
      </header> -->
      <Alert v-if="error" variant="danger">{{ error }}</Alert>
      <Alert v-if="submitted" variant="success" title="Manuscript submitted">
        <p class="mt-2">“{{ submitted.title }}” has been submitted and is pending review.</p>
        <div class="mt-4 flex flex-wrap items-center gap-3">
          <a v-if="submitted.electronic_file" :href="`${storageRoute}/${submitted.electronic_file}`" target="_blank" rel="noopener noreferrer" class="inline-flex h-11 items-center rounded-md border border-emerald-700 bg-transparent px-3.5 text-sm font-medium text-emerald-700 transition-colors hover:bg-emerald-50"> Open submitted file </a>
          <Button type="button" variant="success" @click="submitted = null">Submit another manuscript</Button>
        </div>
      </Alert>
      <Card class="" v-else>
        <CardHeader>
          AcaRepo Form
          <p class="text-muted-foreground text-sm">Please fill up all required fields marked with (<span class="text-danger">*</span>) before submitting.</p>
        </CardHeader>
        <CardBody>
          <Form id="manuscript-form" class="grid gap-5 sm:grid-cols-2" @submit="submit">
            <Control id="manuscript-title" required col class="sm:col-span-2">
              <Label>Title</Label>
              <Input v-model="form.title" :max="255" placeholder="Enter manuscript title" :disabled="loading" />
            </Control>
            <Control id="manuscript-category" required col>
              <Label>Category</Label>
              <Select v-model="form.category" :disabled="loading">
                <Option value="" selected disabled>Select manuscript category</Option>
                <Option value="ut">Undergraduate thesis</Option>
                <Option value="gt">Graduate thesis</Option>
              </Select>
            </Control>
            <Control id="manuscript-language" required col>
              <Label>Language</Label>
              <Select v-model="form.language_id" :disabled="loading || loading">
                <Option value="" selected disabled>Select language</Option>
                <Option v-for="language in options.languages" :key="language.id" :value="language.id">{{ language.name }}</Option>
              </Select>
            </Control>
            <Control class="col-span-2" id="manuscript-library" required col>
              <Label>Library</Label>
              <Select v-model="form.library_id" :disabled="loading || !campusLibraries.length" :error="fieldErrors?.library_id?.[0]">
                <Option :value="null" disabled>Select your campus library</Option>
                <Option v-for="library in campusLibraries" :key="library.id" :value="library.id">{{ library.name }}</Option>
              </Select>
              <p v-if="!loading && !campusLibraries.length" class="text-sm text-danger">No libraries are configured for your campus.</p>
            </Control>
            <Control id="manuscript-abstract" required col class="sm:col-span-2">
              <Label>Abstract</Label>
              <Textarea v-model="form.description" :rows="8" placeholder="Enter your research abstract" :disabled="loading" />
            </Control>
            <Control id="manuscript-doi" nullable col class="sm:col-span-2">
              <Label>DOI</Label>
              <Input v-model="form.doi" :max="255" placeholder="Enter DOI" :disabled="loading" helper="If there is a Digital Object Identifier (DOI) issued for your manuscipt, please include" />
            </Control>
            <Control id="manuscript-keywords" required col class="sm:col-span-2">
              <Label>Keywords</Label>
              <div class="flex flex-col">
                <AddKeywords v-model="form" :disabled="loading" subtitle="Add keywords or subjects for your manuscript." />
                <div class="flex flex-wrap gap-2 mt-5">
                  <p v-if="!form.keywords?.length" class="text-center text-muted-foreground">No keywords yet.</p>
                  <Chip v-for="tag in form.keywords?.slice(0, 1)" :key="tag">{{ tag }}</Chip>
                  <Chip v-if="form.keywords?.length && form.keywords.length > 1" :title="form.keywords?.slice(1).join(', ')">+{{ form.keywords.length - 1 }} more</Chip>
                </div>
              </div>
            </Control>
            <Control id="manuscript-file" col class="sm:col-span-2" required>
              <Label>Thesis File</Label>
              <InputFile accept=".pdf" v-model="form.electronic_file" />
            </Control>
          </Form>
        </CardBody>
        <CardFooter class="flex justify-end">
          <Button form="manuscript-form" type="submit" variant="success" :loading="loading">Submit</Button>
        </CardFooter>
      </Card>
    </div>
  </main>
</template>

<script setup lang="ts">
import AddKeywords from '@/app/librarian/collections/catalog/forms/sections/modals/AddKeywords.vue'

const options = optionStore()
const pop = usePopup()
const auth = authStore()
const acarepo = acaRepoStore()

const defaultForm = (): StoreAcaRepo => ({
  title: 'e-Libra 2.0',
  category: 'ut',
  description: 'A centralized library management system',
  doi: '',
  electronic_file: null,
  keywords: [],

  library_id: null,
  language_id: null,
})

const form = ref<StoreAcaRepo>(defaultForm())
const error = ref<string | null>(null)
const fieldErrors = ref<Record<string, string[]> | null>(null)
const submitted = ref<AcaRepo | null>(null)
const loading = ref<boolean>(false)

const campusLibraries = computed(() => options.libraries?.filter((library) => String(library.campus_id) === String(auth.user?.campus_id)) ?? [])

async function loadOptions() {
  loading.value = true
  error.value = null
  try {
    await Promise.all([
      options.fetch('languages', true),
      options.fetch('libraries', true),
    ])
  } catch (e: any) {
    error.value = e?.response?.data?.message ?? 'Unable to load submission options. Please try again.'
  } finally {
    loading.value = false
  }
}

async function submit() {
  error.value = null
  fieldErrors.value = null
  if (!form.value.keywords?.length) return pop.info('Please enter at least one keyword')
  if (!campusLibraries.value.some((library) => String(library.id) === String(form.value.library_id))) {
    fieldErrors.value = { library_id: ['Select a library that belongs to your campus.'] }
    return
  }

  const confirm = await pop.confirm({ text: 'Are you sure you want to submit this manuscript?' })

  if (confirm.isConfirmed) {
    loading.value = true
    pop.load()
    try {
      const res = await acarepo.create(form.value)
      submitted.value = res.data
      pop.success(res.message)
    } catch (e: any) {
      fieldErrors.value = e?.response?.data?.errors ?? null
      error.value = fieldErrors.value?.library_id?.[0]
        ?? e?.response?.data?.message
        ?? 'Something went wrong, please check your inputs and try again.'
    } finally {
      pop.unload()
      loading.value = false
    }
  }
}

onBeforeMount(loadOptions)
</script>
