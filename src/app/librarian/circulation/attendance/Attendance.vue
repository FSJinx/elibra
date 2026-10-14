<template>
  <div class="size-full flex flex-col gap-5">
    <SectionHeader title="Attendance Tracking" description="Record your library's section based attendance here" icon="calendar" />

    <div class="flex-1 flex items-center justify-center" v-if="!sections.data">
      <p>Your library doesn't have sections yet. Please setup sections before logging attendances.</p>
    </div>

    <div class="flex-1 flex p-5 gap-5" v-else>
      <div class="flex flex-col gap-5 w-200">
        <Card>
          <CardHeader>Tracker</CardHeader>
          <CardBody>
            <Form class="flex flex-col" id="attendnace-recorder">
              <Control col required>
                <Label>Patron ID Number</Label>
                <Input id="patron-id" auto-focus type="text" placeholder="Enter Patron ID number..." />
              </Control>
              <Control col required>
                <Label>Section</Label>
                <Select class="">
                  <Option value="">Select a section</Option>
                  <Option :value="section.id" v-for="section in sections.data">{{ section.name }}</Option>
                </Select>
              </Control>
            </Form>
          </CardBody>
          <CardFooter class="justify-end">
            <Button variant="primary" form="attendance-recorder">Submit</Button>
          </CardFooter>
        </Card>
      </div>
      <Card class="flex-1">
        <CardHeader> Attendance Result </CardHeader>
        <CardBody class="flex flex-col">
          <div class="flex items-start gap-3">
            <!-- Profile Picture Container -->
            <div class="size-50 shrink-0 bg-tertiary border border-border rounded-lg"></div>

            <div class="flex flex-col p-3 flex-1">
              <div class="flex items-start gap-5">
                <Title :level="1">Juan A. Dela Cruz </Title>
                <Status variant="patron">Student</Status>
              </div>
              <Title :level="2"> {{ patron.id_number }} </Title>
              <p class="text-sm">ID Number: <span class="font-semibold">2x-3348</span></p>
              <p class="text-sm">Department: <span class="font-semibold">College of Computing Studies, Communication Information, and Technology</span></p>
              <p class="text-sm">Department: <span class="font-semibold">BS Information Technology</span></p>

              <p class="text-sm mt-auto">Last Entered: <span class="font-semibold">1 min ago</span></p>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <Control col>
              <Label>Program</Label>
              <Input id="" type="text" placeholder="" readonly />
            </Control>
            <Control col>
              <Label>Last Visited</Label>
              <Input id="" type="text" placeholder="" readonly />
            </Control>
          </div>
        </CardBody>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
const sections = sectionStore()

const patron = reactive({
  name: '',
  id_number: '2x-xxxx',
  department: '',
  program: '',
})
</script>

<style scoped></style>
