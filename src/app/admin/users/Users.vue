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
            <th class="text-left">Middle Initial</th>
            <th>Sex</th>
            <th class="text-left">Email</th>
            <th>Status</th>
          </tr>
        </Thead>

        <Tbody :data="users.data" :loading="users.loading" cols="7">
          <tr v-for="(user, index) in users.data" :key="index">
            <Td :data="index + 1" />
            <Td :data="user.last_name" class="text-left"/>
            <Td :data="user.first_name" class="text-left"/>
            <Td :data="user.middle_initial" class="text-left"/>
            <Td>
              <Status :variant="parse.status(user.sex)">{{ user.sex }}</Status>
            </Td>
            <Td :data="user.email" class="text-left"/>
            <Td>
              <Status :variant="parse.status(user.status)">{{ user.status }}</Status>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
const users = useAdminUserStore()
const parse = useParser()

onBeforeMount(() => {
  if (users.data === null) {
    users.fetch()
  }
})
</script>

<style scoped></style>
