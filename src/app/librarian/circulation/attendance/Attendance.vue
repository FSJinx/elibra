<template>
  <div class="size-full flex min-h-0 flex-col gap-5">
    <SectionHeader title="Attendance Tracking" description="Record library attendance and monitor hourly patron traffic" icon="calendar" />

    <div class="flex-1 place-items-center place-content-center space-y-5" v-if="loading">
      <Spinner />
      <p>Fetching library sections, please wait...</p>
    </div>

    <div class="flex-1 flex items-center justify-center" v-else-if="!librarySections.length">
      <p>Your library doesn't have sections yet. Please setup sections before logging attendances.</p>
    </div>

    <div class="flex-1 flex min-h-0 p-5 gap-5" v-else>
      <Card class="flex-1 min-h-0 overflow-hidden">
        <CardHeader class="flex items-center justify-between gap-3">
          <span>Attendance Result</span>
        </CardHeader>

        <CardBody class="place-items-center place-content-center" v-if="attendanceLoading">
          <Spinner />
          <p>Checking...</p>
        </CardBody>

        <CardBody class="flex flex-col items-center justify-center gap-4 text-center" v-else-if="errorMessage">
          <div class="flex size-12 items-center justify-center rounded-full bg-danger/10 text-danger">
            <Icon icon="x-circle" />
          </div>
          <p class="max-w-sm text-sm text-foreground-secondary">{{ errorMessage }}</p>
          <Button size="sm" variant="default" @click="errorMessage = null">Back to analytics</Button>
        </CardBody>

        <CardBody v-else-if="!patron" class="flex min-h-0 flex-col gap-5 overflow-y-auto">
          <Select id="analytics-location" title="Analytics Location" class="ml-auto max-w-60 w-full" v-model="analyticsSectionId">
            <Option value="">Overall</Option>
            <Option :value="section.id" v-for="section in librarySections">{{ section.name }}</Option>
          </Select>
          <section class="flex-1 rounded-xl border border-border p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
              <div>
                <h2 class="text-sm font-semibold">Patron Attendance by Hour</h2>
                <p class="mt-1 text-xs text-foreground-secondary">{{ analytics.scope_label }} · Grouped by gender for today</p>
              </div>
              <div class="flex gap-4 text-xs text-foreground-secondary">
                <span class="flex items-center gap-1.5"><i class="size-2.5 rounded-full bg-info"></i>Male</span>
                <span class="flex items-center gap-1.5"><i class="size-2.5 rounded-full bg-red-500"></i>Female</span>
              </div>
            </div>

            <div v-if="analyticsLoading" class="flex h-full items-center justify-center">
              <Spinner />
            </div>
            <div v-else class="mt-5 flex h-64 items-end gap-1.5 border-b border-border px-2">
              <div v-for="hour in analytics.hourly" :key="hour.hour" class="group relative flex h-full min-w-3 flex-1 items-end justify-center gap-0.5" :title="`${hour.label}: ${hour.male} male, ${hour.female} female`">
                <div class="w-[44%] min-h-1 rounded-t-sm bg-info/80" :style="{ height: `${barHeight(hour.male)}%` }"></div>
                <div class="w-[44%] min-h-1 rounded-t-sm bg-red-500/80" :style="{ height: `${barHeight(hour.female)}%` }"></div>
                <span v-if="hour.hour % 2 === 7 || hour.hour % 2 === 1" class="absolute -bottom-6 text-[10px] text-foreground-secondary">{{ hour.label.replace(' ', '') }}</span>
              </div>
            </div>

            <div class="mt-10 grid gap-3 sm:grid-cols-3">
              <div class="rounded-xl border border-border bg-secondary/30 p-4">
                <p class="text-xs text-foreground-secondary">Total attendance</p>
                <p class="mt-1 text-2xl font-semibold">{{ analytics.total }}</p>
              </div>
              <div class="rounded-xl border border-border bg-secondary/30 p-4">
                <p class="text-xs text-foreground-secondary">Checked in</p>
                <p class="mt-1 text-2xl font-semibold text-success">{{ analytics.checked_in }}</p>
              </div>
              <div class="rounded-xl border border-border bg-secondary/30 p-4">
                <p class="text-xs text-foreground-secondary">Checked out</p>
                <p class="mt-1 text-2xl font-semibold text-primary">{{ analytics.checked_out }}</p>
              </div>
            </div>
          </section>

          <section class="grid gap-5 lg:grid-cols-2">
            <div class="rounded-xl border border-border p-5">
              <h2 class="text-sm font-semibold">{{ analytics.scope_label }} Traffic</h2>
              <p class="mt-1 text-xs text-foreground-secondary">Check-in and check-out distribution</p>
              <div class="mt-4 space-y-3">
                <div v-for="row in analytics.sections" :key="row.name" class="space-y-1.5">
                  <div class="flex justify-between gap-3 text-xs">
                    <span class="truncate text-foreground-secondary">{{ row.name }}</span>
                    <span class="font-semibold">{{ row.total }}</span>
                  </div>
                  <div class="h-2 overflow-hidden rounded-full bg-secondary">
                    <div class="h-full rounded-full bg-primary" :style="{ width: `${percentage(row.total, analytics.sections)}%` }"></div>
                  </div>
                </div>
                <p v-if="!analytics.sections.length" class="py-5 text-center text-xs text-foreground-secondary">No section attendance recorded for this date.</p>
              </div>
            </div>

            <div class="rounded-xl border border-border p-5">
              <h2 class="text-sm font-semibold">Most Frequent Patrons</h2>
              <p class="mt-1 text-xs text-foreground-secondary">Top recorded attendance for this date</p>
              <div class="mt-3 divide-y divide-border">
                <div v-for="row in analytics.top_patrons" :key="row.username" class="flex items-center gap-3 py-2.5">
                  <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary">
                    {{ initials(row.name) }}
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium">{{ row.name }}</p>
                    <p class="text-xs text-foreground-secondary">{{ row.patron_type ?? 'Patron' }}</p>
                  </div>
                  <span class="rounded-full bg-secondary px-2 py-1 text-xs font-semibold">{{ row.total }}</span>
                </div>
                <p v-if="!analytics.top_patrons.length" class="py-5 text-center text-xs text-foreground-secondary">No patron attendance recorded for this date.</p>
              </div>
            </div>
          </section>
        </CardBody>

        <CardBody class="flex min-h-0 flex-col gap-5 overflow-y-auto p-5" v-else>
          <div class="relative overflow-hidden rounded-2xl border border-primary/15 bg-linear-to-br from-primary/10 via-secondary/40 to-success/10 p-5">
            <div class="absolute -right-10 -top-16 size-44 rounded-full bg-primary/5"></div>
            <div class="relative flex flex-col items-center gap-4 text-center sm:flex-row sm:text-left">
              <div class="relative shrink-0">
                <div class="size-28 overflow-hidden rounded-2xl border-4 border-background bg-tertiary shadow-lg">
                  <img :src="user" alt="Patron profile" class="size-full object-cover" />
                </div>
                <span class="absolute -bottom-2 left-1/2 flex -translate-x-1/2 items-center gap-1 whitespace-nowrap rounded-full border-4 border-background bg-success px-2.5 py-1 text-[11px] font-semibold text-white">
                  <span class="size-1.5 rounded-full bg-white"></span>
                  Checked in
                </span>
              </div>

              <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-primary">{{ patron.patron_type?.name ?? 'Patron' }}</p>
                <h2 class="mt-1 truncate text-2xl font-bold text-foreground">{{ patron.user.name?.full }}</h2>
                <p class="mt-1 text-sm text-foreground-secondary">{{ patron.program?.name ?? 'No program assigned' }}</p>
              </div>

              <div class="grid grid-cols-2 gap-2 sm:w-52">
                <div class="rounded-xl border border-border/70 bg-background/80 px-3 py-2.5">
                  <p class="text-[10px] uppercase tracking-wider text-foreground-secondary">ID Number</p>
                  <p class="mt-1 truncate text-sm font-semibold">{{ patron.user.username ?? 'No Data' }}</p>
                </div>
                <div class="rounded-xl border border-border/70 bg-background/80 px-3 py-2.5">
                  <p class="text-[10px] uppercase tracking-wider text-foreground-secondary">EBC Number</p>
                  <p class="mt-1 truncate text-sm font-semibold">{{ patron.ebc_number ?? 'No Data' }}</p>
                </div>
              </div>
            </div>
          </div>

          <div>
            <div class="mb-3 flex items-center justify-between">
              <h3 class="text-sm font-semibold">Patron Information</h3>
              <span class="rounded-full bg-success/10 px-2.5 py-1 text-xs font-medium text-success">Active record</span>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
              <div class="rounded-xl border border-border p-4">
                <p class="text-xs font-medium text-foreground-secondary">Full Name</p>
                <p class="mt-1.5 font-semibold">{{ patron.user.name?.full ?? '-' }}</p>
              </div>
              <div class="rounded-xl border border-border p-4">
                <p class="text-xs font-medium text-foreground-secondary">Patron Type</p>
                <p class="mt-1.5 font-semibold">{{ patron.patron_type?.name ?? 'No Data' }}</p>
              </div>
              <div class="rounded-xl border border-border p-4">
                <p class="text-xs font-medium text-foreground-secondary">ID Number</p>
                <p class="mt-1.5 font-semibold">{{ patron.user.username ?? 'No Data' }}</p>
              </div>
              <div class="rounded-xl border border-border p-4">
                <p class="text-xs font-medium text-foreground-secondary">EBC Number</p>
                <p class="mt-1.5 font-semibold">{{ patron.ebc_number ?? 'No Data' }}</p>
              </div>
              <div class="rounded-xl border border-border p-4 sm:col-span-2">
                <p class="text-xs font-medium text-foreground-secondary">Program</p>
                <p class="mt-1.5 font-semibold">{{ patron.program?.name ?? 'No data' }}</p>
              </div>
            </div>
          </div>
        </CardBody>
      </Card>

      <div class="flex w-200 shrink-0 flex-col gap-5">
        <Card>
          <CardHeader>Tracker</CardHeader>
          <CardBody>
            <Form class="flex flex-col gap-3" id="attendance-recorder" @submit="submit()">
              <Control col required>
                <Label>Patron ID Number</Label>
                <Input id="patron-id" auto-focus type="text" placeholder="Enter Patron ID number..." v-model="form.patron" />
              </Control>
              <Control col>
                <Label>Section</Label>
                <Select class="" v-model="form.section_id" title="Sections">
                  <Option value=""> Entrance</Option>
                  <Option :value="section.id" v-for="section in librarySections">{{ section.name }}</Option>
                </Select>
              </Control>
            </Form>
          </CardBody>
          <CardFooter class="justify-end">
            <Button variant="primary" type="submit" form="attendance-recorder">Submit</Button>
          </CardFooter>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import user from '@/assets/images/user.png'

const options = optionStore()
const loading = ref<boolean>(false)
const attendanceLoading = ref<boolean>(false)

const auth = authStore()
const attendance = attendanceStore()
const time = ref()
const errorMessage = ref(null)
const analyticsLoading = ref(false)
const analytics = ref({
  date: new Date().toISOString().slice(0, 10),
  scope_label: 'General / Overall',
  total: 0,
  checked_in: 0,
  checked_out: 0,
  hourly: [] as Array<{ hour: number; label: string; male: number; female: number; total: number }>,
  statuses: [] as Array<{ status: string; total: number }>,
  sections: [] as Array<{ name: string; total: number }>,
  top_patrons: [] as Array<{ name: string; username: string; patron_type: string | null; total: number }>,
})

const patron = ref<Patron | null>(null)
const analyticsSectionId = ref('')
const form = ref({
  patron: '',
  section_id: '',
  library_id: auth.user?.library?.id ?? null,
})

const librarySections = computed(() => (options.sections ?? []).filter((section) => Number(section.library_id) === Number(auth.user?.library?.id)))

async function loadSections() {
  loading.value = true
  try {
    await options.load(['sections'])
  } finally {
    loading.value = false
  }
}

async function submit() {
  attendanceLoading.value = true
  clearTimeout(time.value)
  errorMessage.value = null

  try {
    const res = await attendance.create(form.value)
    patron.value = res.data.patron
    await fetchAnalytics()

    time.value = setTimeout(() => {
      patron.value = null
    }, 5000)
  } catch (error: any) {
    const errors = error?.response?.data?.errors
    errorMessage.value = errors ? (Object.values(errors).flat()[0] as string) : (error?.response?.data?.message ?? 'Unable to record attendance.')
  } finally {
    attendanceLoading.value = false
  }

  form.value.patron = '' // ready na para sa susunod na scan
}

async function fetchAnalytics() {
  analyticsLoading.value = true
  try {
    const res = await get<{ data: typeof analytics.value }>('attendance/analytics', {
      library_id: auth.user?.library?.id,
      section_id: analyticsSectionId.value || null,
    })
    analytics.value = res.data
  } catch (error: any) {
    analytics.value = {
      date: new Date().toISOString().slice(0, 10),
      scope_label: analyticsSectionId.value ? 'Library Section' : 'Entrance',
      total: 0,
      checked_in: 0,
      checked_out: 0,
      hourly: [],
      statuses: [],
      sections: [],
      top_patrons: [],
    }
  } finally {
    analyticsLoading.value = false
  }
}

const analyticsMax = computed(() => Math.max(1, ...analytics.value.hourly.flatMap((hour) => [hour.male, hour.female])))

function barHeight(value: number) {
  return Math.max(value ? 8 : 2, (Number(value) / analyticsMax.value) * 100)
}

function percentage(value: number, rows: Array<{ total: number }>) {
  const total = rows.reduce((sum, row) => sum + Number(row.total), 0)
  return total ? Math.max(2, (Number(value) / total) * 100) : 0
}

function initials(name: string) {
  return String(name ?? '')
    .split(' ')
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => part[0])
    .join('')
    .toUpperCase()
}

watch(analyticsSectionId, () => fetchAnalytics())

onMounted(async () => {
  await loadSections()
  await fetchAnalytics()
})
</script>

<style scoped></style>
