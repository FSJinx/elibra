<template>
  <div class="size-full flex flex-col gap-5">
    <SectionHeader title="Attendance Tracking" description="Record your library's section based attendance here" icon="calendar" />

    <div class="flex-1 place-items-center place-content-center space-y-5" v-if="loading">
      <Spinner />
      <p>Fetching library sections, please wait...</p>
    </div>

    <div class="flex-1 flex items-center justify-center" v-else-if="!sections.data">
      <p>Your library doesn't have sections yet. Please setup sections before logging attendances.</p>
    </div>

    <div class="flex-1 flex p-5 gap-5" v-else>
      <Card class="flex-1">
        <CardHeader> Attendance Result </CardHeader>

        <CardBody class="place-items-center place-content-center" v-if="attendanceLoading">
          <Spinner />
          <p>Checking...</p>
        </CardBody>

        <CardBody class="place-items-center place-content-center" v-else-if="errorMessage">
          <p>{{ errorMessage }}</p>
        </CardBody>

        <CardBody class="place-items-center place-content-center" v-else-if="!patron">
          <p>No patron.</p>
        </CardBody>

        <CardBody class="flex flex-col" v-else>
          <div class="flex flex-col items-center gap-3">
            <!-- Profile Picture Container -->
            <div class="size-40 shrink-0 bg-tertiary border border-border rounded-full">
              <img :src="user" alt="" class="" />
            </div>

            <div class="flex flex-col items-center p-3 flex-1">
              <div class="flex items-center gap-5">
                <Title :level="1">{{ patron?.user.name?.full }}</Title>
                <Status variant="patron">Student</Status>
              </div>

              <p class="text-sm mt-auto">Last Entered: <span class="font-semibold">1 min ago</span></p>
            </div>
          </div>

          <div class="flex flex-col gap-2">
            <Control col>
              <Label>Name</Label>
              <p class="px-5 py-3 border border-border rounded-lg bg-secondary">{{ patron.user.name?.full ?? '-' }}</p>
            </Control>
            <Control col>
              <Label>ID Number</Label>
              <p class="px-5 py-3 border border-border rounded-lg bg-secondary">{{ patron.user.username ?? 'No Data' }}</p>
            </Control>
            <Control col>
              <Label>Patron Type</Label>
              <p class="px-5 py-3 border border-border rounded-lg bg-secondary">{{ patron.patron_type?.name ?? 'No Data' }}</p>
            </Control>
            <Control col>
              <Label>EBC Number</Label>
              <p class="px-5 py-3 border border-border rounded-lg bg-secondary">{{ patron.ebc_number ?? 'No Data' }}</p>
            </Control>
            <Control col>
              <Label>Program</Label>
              <p class="px-5 py-3 border border-border rounded-lg bg-secondary">{{ patron.program?.name ?? 'No data' }}</p>
            </Control>
          </div>
        </CardBody>
      </Card>
      
      <div class="flex flex-col gap-5 w-200">
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
                <Select class="" v-model="form.section_id">
                  <Option value="">Select a section</Option>
                  <Option :value="section.id" v-for="section in sections.data">{{ section.name }}</Option>
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

const sections = sectionStore()
const loading = ref<boolean>(false)
const attendanceLoading = ref<boolean>(false)

const auth = authStore()
const attendance = attendanceStore()
const parse = useParser()
const time = ref()
const errorMessage = ref(null)

const patron = ref<Patron | null>(null)
const form = ref({
  patron: '',
  section_id: '',
  library_id: auth.user?.library?.id ?? null,
})

async function fetchSections() {
  loading.value = true
  try {
    await sections.fetch()
  } finally {
    loading.value = false
  }
}

async function submit() {
  attendanceLoading.value = true
  clearTimeout(time.value)

  try {
    const res = await attendance.create(form.value)
    patron.value = res.data.patron

    time.value = setTimeout(() => {
      patron.value = null
    }, 5000)
  } finally {
    attendanceLoading.value = false
  }

  form.value.patron = '' // ready na para sa susunod na scan
}

onMounted(() => fetchSections())
</script>

<style scoped></style>
