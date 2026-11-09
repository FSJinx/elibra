<template>
  <div class="size-full flex-1 flex flex-col overflow-hidden">
    <SectionHeader title="Users" description="Manage all users of the system" icon="people">
      <div class="flex items-center justify-end">
        <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.users.new' }">New User</Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-5 p-5 overflow-y-auto">
      <Card class="">
        <CardBody class="flex items-center gap-2">
          <Select title="Campuses" v-model="params.campus_id" class="max-w-max">
            <Option value="">All Campus</Option>
            <Option :value="campus.id" v-for="campus in options.campuses" :label="campus.name" />
          </Select>
          <Select title="Role" v-model="params.role" class="max-w-max">
            <Option value="">All Roles</Option>
            <Option value="super_admin" label="Super Admin" />
            <Option value="admin" label="Admin" />
            <Option value="librarian" label="Librarian" />
            <Option value="patron" label="Patron" />
          </Select>
          <Button class="ml-auto" @click="reset">Reset Filters</Button>
        </CardBody>
      </Card>
      <Table title="All Users" subtitle="List of all e-Libra users" :data="pagination">
        <template #header>
          <Form id="search-users" @submit="search" class="flex items-center justify-end w-full gap-2">
            <Input class="max-w-125" id="search-query" type="text" placeholder="Search by user's name, username, email, or id number" v-model="params.query" enable-clear />
            <Button type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <th>No</th>
            <th class="text-left">Name</th>
            <th>Role</th>
            <th>Username</th>
            <th>Sex</th>
            <th class="text-left">Email</th>
            <th>Status</th>
            <th>Registration</th>
            <th>Actions</th>
          </tr>
        </Thead>

        <Tbody :data="users.data" :loading="loading" cols="10">
          <tr v-for="(user, index) in users.data" :key="index">
            <Td :data="index + 1" />
            <Td :data="user.name?.formal" class="text-left" />
            <Td>
              <Status :variant="parse.status(user.role)">{{ user.role_text }}</Status>
            </Td>
            <Td :data="user.username" />
            <Td>
              <Status :variant="parse.status(user.sex)">{{ user.sex }}</Status>
            </Td>
            <Td :data="user.email" class="text-left" />
            <Td>
              <Status :variant="parse.status(user.status)">{{ user.status }}</Status>
            </Td>
            <Td>
              <Status :variant="parse.status(user.registration ?? 'not reviewed')">
                {{ user.registration ?? 'not reviewed' }}
              </Status>
            </Td>
            <Td>
              <Button size="sm" variant="text" @click="viewUser(user)">View</Button>
              <Button size="sm" variant="text" class="text-danger" @click="deleteUser(user)">Delete</Button>
            </Td>
          </tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>

    <Modal ref="modal" size="large">
      <ModalHeader use-default-layout title="User information" :subtitle="selectedUser ? `${selectedUser.first_name} ${selectedUser.last_name}` : ''" icon="person" />
      <ModalBody v-if="selectedUser">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5">
          <div>
            <p class="text-xs text-muted-foreground">Full name</p>
            <p>{{ [selectedUser.first_name, selectedUser.middle_initial, selectedUser.last_name].filter(Boolean).join(' ') }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Username</p>
            <p>{{ selectedUser.username || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Email</p>
            <p>{{ selectedUser.email || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Contact number</p>
            <p>{{ selectedUser.contact_number || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Sex</p>
            <p>{{ selectedUser.sex || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Birthdate</p>
            <p>{{ selectedUser.birthdate || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Account role</p>
            <p>{{ selectedUser.role || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">EBC number</p>
            <p>{{ selectedUser.patron?.ebc_number || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Patron type</p>
            <p>{{ selectedUser.patron?.patron_type?.name || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Program</p>
            <p>{{ selectedUser.patron?.program?.name || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Campus</p>
            <p>{{ selectedUser.campus?.name || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Registration status</p>
            <Status :variant="parse.status(selectedUser.registration ?? 'not reviewed')">
              {{ selectedUser.registration ?? 'not reviewed' }}
            </Status>
          </div>
        </div>
      </ModalBody>
      <ModalFooter class="gap-2">
        <Button variant="text" @click="modal?.close()">Close</Button>
        <template v-if="selectedUser?.registration === 'pending'">
          <Button variant="danger" :disabled="reviewing" @click="reviewRegistration('reject')">Reject</Button>
          <Button variant="success" :disabled="reviewing" @click="reviewRegistration('approve')">Approve</Button>
        </template>
      </ModalFooter>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const users = userStore()
const parse = useParser()
const pop = usePopup()
const options = optionStore()

const modal = ref<typeof Modal | null>(null)
const selectedUser = ref<User | null>(null)
const reviewing = ref(false)
const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const defaultParams = (): Partial<UserParams> => ({
  query: '',
  sort: 'last_name',
  campus_id: '',
})
const params = reactive<Partial<UserParams>>(defaultParams())

async function reset() {
  Object.assign(params, defaultParams())
  nextTick(search)
}

async function search() {
  loading.value = true
  try {
    const res = await users.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

function viewUser(user: User) {
  selectedUser.value = user
  modal.value?.open()
}

async function reviewRegistration(action: 'approve' | 'reject') {
  if (!selectedUser.value || selectedUser.value.registration !== 'pending') return

  const actionLabel = action === 'approve' ? 'approve' : 'reject'
  const pastTense = action === 'approve' ? 'approved' : 'rejected'
  const confirmation = await pop.confirm({
    text: `Are you sure you want to ${actionLabel} ${selectedUser.value.first_name} ${selectedUser.value.last_name}'s registration?`,
  })

  if (!confirmation.isConfirmed) return

  reviewing.value = true
  pop.load()
  try {
    const userUuid = selectedUser.value.uuid
    const result = action === 'approve' ? await users.approveRegistration(selectedUser.value) : await users.rejectRegistration(selectedUser.value)

    await users.fetch()
    selectedUser.value = users.data?.find((user) => user.uuid === userUuid) ?? null
    await pop.success(result.message ?? `User registration ${pastTense} successfully.`)
    if (!selectedUser.value) modal.value?.close()
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? `Unable to ${actionLabel} this registration.`)
  } finally {
    reviewing.value = false
  }
}

async function deleteUser(user: User) {
  const confirmation = await pop.confirm({
    text: `Delete ${user.first_name} ${user.last_name}'s user record?`,
    confirmButtonText: 'Delete',
  })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const result = await users.delete(user)
    await users.fetch()
    if (selectedUser.value?.uuid === user.uuid) {
      selectedUser.value = null
      modal.value?.close()
    }
    await pop.success(result.message ?? 'User record deleted successfully.')
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to delete this user record.')
  }
}

watchDebounced(params, search, { debounce: 300, deep: 300, immediate: true })
</script>

<style scoped></style>
