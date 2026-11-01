<template>
  <div class="size-full flex-1 flex flex-col gap-5">
    <SectionHeader title="Users" description="Manage all users of the system" icon="people"></SectionHeader>

    <div class="flex-1 flex flex-col gap-5 p-5">
      <Table title="User Records" subtitle="List of all e-Libra users" data-length="25">
        <Thead>
          <tr>
            <th>No</th>
            <th class="text-left">Last Name</th>
            <th class="text-left">First Name</th>
            <th>Middle Initial</th>
            <th>Sex</th>
            <th>Username</th>
            <th class="text-left">Email</th>
            <th>Status</th>
            <th>Registration</th>
            <th>Actions</th>
          </tr>
        </Thead>

        <Tbody :data="users.data" :loading="users.loading" cols="10">
          <tr v-for="(user, index) in users.data" :key="index">
            <Td :data="index + 1" />
            <Td :data="user.last_name" class="text-left" />
            <Td :data="user.first_name" class="text-left" />
            <Td :data="user.middle_initial" />
            <Td>
              <Status :variant="parse.status(user.sex)">{{ user.sex }}</Status>
            </Td>
            <Td :data="user.username" />
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
      </Table>
    </div>

    <Modal ref="modal" size="large">
      <ModalHeader
        use-default-layout
        title="User information"
        :subtitle="selectedUser ? `${selectedUser.first_name} ${selectedUser.last_name}` : ''"
        icon="person"
      />
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

const users = useAdminUserStore()
const parse = useParser()
const pop = usePopup()
const modal = ref<typeof Modal | null>(null)
const selectedUser = ref<User | null>(null)
const reviewing = ref(false)

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
    const result =
      action === 'approve'
        ? await users.approveRegistration(selectedUser.value)
        : await users.rejectRegistration(selectedUser.value)

    await users.fetch(true)
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
    await users.fetch(true)
    if (selectedUser.value?.uuid === user.uuid) {
      selectedUser.value = null
      modal.value?.close()
    }
    await pop.success(result.message ?? 'User record deleted successfully.')
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to delete this user record.')
  }
}

onBeforeMount(() => {
  if (users.data === null) {
    users.fetch()
  }
})
</script>

<style scoped></style>
