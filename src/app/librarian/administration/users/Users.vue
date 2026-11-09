<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Users" description="Manage admins, librarians, staffs, and other in this library." icon="person-vcard">
      <div class="flex items-center justify-end">
        <Button as="link" :to="{ name: 'librarian.users.new' }" variant="primary" left-icon="plus-lg">New User</Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-5 overflow-hidden">
      <Table title="All Users" subtitle="These are the users under this library" :data="pagination">
        <template #header>
          <Form id="user-search" @submit="search" class="flex items-center justify-end gap-2 w-full">
            <Input id="search-staff" type="text" placeholder="Search by name..." class="max-w-100" />
            <Button type="submit">Search</Button>
          </Form>
        </template>

        <Thead>
          <tr>
            <Th>No</Th>
            <Th class="text-left">Name</Th>
            <Th>Role</Th>
            <Th>Username / ID Number</Th>
            <Th>Sex</Th>
            <Th>Registration Status</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>

        <Tbody :data="users.data" :loading="loading">
          <Tr v-for="(user, index) in users.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left">
              <p class="font-semibold">{{ user.name?.full }}</p>
              <p class="text-muted-foreground">{{ user.email ?? 'No email provided' }}</p>
            </Td>
            <Td>
              <Status :variant="user.role">{{ user.role }}</Status>
            </Td>
            <Td :data="user.username" />
            <Td>
              <Status :variant="user.sex">{{ user.sex }}</Status>
            </Td>
            <Td>
              <Status :variant="user.registration as string">{{ user.registration ?? 'Not applicable' }}</Status>
            </Td>
            <Td class="space-x-2">
              <Button class="hover:text-info" size="sm" @click="viewUser(user)">View</Button>
              <Button class="hover:text-danger" size="sm" @click="deleteUser(user)">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>

    <Modal ref="modal" size="large">
      <ModalHeader use-default-layout title="User information" :subtitle="selectedUser ? `${selectedUser.first_name} ${selectedUser.last_name}` : ''" icon="person" />
      <ModalBody v-if="selectedUser">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5">
          <Dl>
            <Dt>Full Name</Dt>
            <Dd>{{ selectedUser.name?.full }}</Dd>
          </Dl>
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
            <p class="text-xs text-muted-foreground">Role</p>
            <p>{{ selectedUser.role || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Account status</p>
            <p>{{ selectedUser.status || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Campus</p>
            <p>{{ selectedUser.campus?.name || '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Library</p>
            <p>{{ selectedUser.library?.name || selectedUser.librarian?.library?.name || '—' }}</p>
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
            <p class="text-xs text-muted-foreground">Registration status</p>
            <p>{{ selectedUser.registration || 'not reviewed' }}</p>
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
const pop = usePopup()
const auth = authStore()

const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const params = reactive<UserParams>({
  query: '',
  campus_id: auth.user?.campus_id ?? null,
})

const tableError = ref()
const modal = ref<typeof Modal | null>(null)
const selectedUser = ref<User | null>(null)
const reviewing = ref(false)

function viewUser(user: User) {
  selectedUser.value = user
  modal.value?.open()
}

async function reviewRegistration(action: 'approve' | 'reject') {
  const user = selectedUser.value
  if (!user || user.registration !== 'pending') return

  const actionLabel = action === 'approve' ? 'approve' : 'reject'
  const confirmation = await pop.confirm({
    text: `Are you sure you want to ${actionLabel} ${user.first_name} ${user.last_name}'s registration?`,
  })
  if (!confirmation.isConfirmed) return

  reviewing.value = true
  pop.load()
  try {
    const result = action === 'approve' ? await users.approveRegistration(user) : await users.rejectRegistration(user)
    await users.fetch()
    selectedUser.value = users.data?.find((item) => item.uuid === user.uuid) ?? null
    await pop.success(result.message ?? `User registration ${action === 'approve' ? 'approved' : 'rejected'} successfully.`)
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
    await pop.success(result.message ?? 'User record deleted successfully.')
    if (selectedUser.value?.uuid === user.uuid) modal.value?.close()
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to delete this user record.')
  }
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

watchDebounced(() => params, search, { debounce: 300, deep: true, immediate: true })
</script>

<style scoped></style>
