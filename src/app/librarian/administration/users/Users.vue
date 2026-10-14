<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Library Staffs" description="Manage admins, librarians, staffs, and other in this library." icon="person-vcard">
      <div class="flex items-center justify-end">
        <NewUser />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-5 overflow-hidden">
      <Card>
        <Form id="user-search" @submit="search" class="flex items-center gap-2 w-full">
          <Input id="search-staff" type="text" placeholder="Search by name..." class="max-w-100" />
          <Button type="submit" variant="info">Refresh</Button>
        </Form>
      </Card>

      <Table title="All Users" subtitle="These are the users under this library" :data-length="users.data?.length ?? 0">
        <Thead>
          <tr>
            <Th class="text-left">Name</Th>
            <Th>Username</Th>
            <Th>Sex</Th>
            <Th>Role</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>

        <Tbody :data="users.data" :loading="loading" cols="6" :error-message="tableError">
          <Tr v-for="(user, index) in users.data" :key="index">
            <Td class="text-left">
              <p class="font-semibold">{{ user.first_name }} {{ user.middle_initial ? user.middle_initial + '.' : '' }} {{ user.last_name }}</p>
              <p class="text-muted-foreground">{{ user.email ?? 'No email provided' }}</p>
            </Td>
            <Td :data="user.username" />
            <Td>
              <Status :variant="user.sex">{{ user.sex }}</Status>
            </Td>
            <Td>
              <Status :variant="user.role">{{ user.role }}</Status>
            </Td>
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
import NewUser from '@/app/librarian/administration/users/modals/NewUser.vue'

const loading = ref<boolean>(false)
const users = usersStore()
const pop = usePopup()
const tableError = ref()

async function search() {
  loading.value = true
  try {
    await users.fetch()
  } catch (e: any) {
    console.log(e.response)
  } finally {
    loading.value = false
  }
}

onBeforeMount(() => search())
</script>

<style scoped></style>
