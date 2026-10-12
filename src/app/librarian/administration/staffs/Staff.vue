<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Library Staffs" description="Manage admins, librarians, staffs, and other in this library." icon="account">
      <div class="flex items-center justify-end">
        <NewLibrarian />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-5 overflow-hidden">
      <Card>
        <CardBody class="flex items-center gap-2">
          <Input id="search-staff" type="text" placeholder="Search by name..." class="max-w-100" />
          <Button @click="search">Refresh</Button>
        </CardBody>
      </Card>

      <Table title="Library Staffs" subtitle="These are the library staffs for this library" data-length="">
        <Thead>
          <tr>
            <Th class="text-left">Name</Th>
            <Th>Username</Th>
            <Th>Sex</Th>
            <Th>Role</Th>
            <Th>Assigned Section</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>

        <Tbody :data="data" :loading="loading" cols="4">
          <Tr v-for="(librarian, index) in data" :key="index">
            <Td class="text-left">
              <p class="font-semibold">{{ librarian.first_name }} {{ librarian.middle_initial ? librarian.middle_initial + '.' : '' }} {{ librarian.last_name }}</p>
              <p class="text-muted-foreground">{{ librarian.email ?? 'Email is not provided' }}</p>
            </Td>
            <Td :data="librarian.username" />
            <Td>
              <Status :variant="librarian.sex">{{ librarian.sex }}</Status>
            </Td>
            <Td>
              <Status :variant="librarian.role">{{ librarian.role }}</Status>
            </Td>
            <Td :data="librarian.section" />
            <Td class="space-x-2">
              <Button class="hover:text-info" size="sm">View</Button>
              <Button class="hover:text-danger" size="sm">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewLibrarian from '@/app/librarian/administration/staffs/modals/NewLibrarian.vue'

const loading = ref<boolean>(false)
const librarians = librariansStore()
const pop = usePopup()

// const data = ref<User>()
const data = ref([
  { last_name: 'Data 1', first_name: 'Dummy', middle_initial: 'A', username: 'dummydata1', sex: 'male', role: 'librarian', email: 'dummydata@email.com', section: 'General Section' },
  { last_name: 'Data 2', first_name: 'Dummy', middle_initial: 'B', username: 'dummydata2', sex: 'female', role: 'librarian', email: null, section: 'General Section' },
  { last_name: 'Data 3', first_name: 'Dummy', middle_initial: 'C', username: 'dummydata3', sex: 'male', role: 'admin', email: null, section: 'General Section' },
  { last_name: 'Data 4', first_name: 'Dummy', middle_initial: 'D', username: 'dummydata4', sex: 'female', role: 'librarian', email: null, section: 'General Section' },
  { last_name: 'Data 5', first_name: 'Dummy', middle_initial: 'E', username: 'dummydata5', sex: 'male', role: 'librarian', email: null, section: 'General Section' },
  { last_name: 'Data 6', first_name: 'Dummy', middle_initial: 'F', username: 'dummydata6', sex: 'female', role: 'admin', email: null, section: 'General Section' },
  { last_name: 'Data 7', first_name: 'Dummy', middle_initial: 'G', username: 'dummydata7', sex: 'male', role: 'librarian', email: null, section: 'General Section' },
  { last_name: 'Data 8', first_name: 'Dummy', middle_initial: 'H', username: 'dummydata8', sex: 'female', role: 'librarian', email: null, section: 'General Section' },
  { last_name: 'Data 9', first_name: 'Dummy', middle_initial: 'I', username: 'dummydata9', sex: 'male', role: 'admin', email: null, section: 'General Section' },
  { last_name: 'Data 10', first_name: 'Dummy', middle_initial: 'J', username: 'dummydata10', sex: 'female', role: 'librarian', email: null, section: 'General Section' },
])

async function search() {
  loading.value = true
  try {
    const res = await librarians.fetch()

    console.log(res.data)

    data.value = res.data
  } finally {
    loading.value = false
  }
}

// onBeforeMount(() => {
//   search()
// })
</script>

<style scoped></style>
