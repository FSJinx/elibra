<template>
  <Table name="Users Table" description="People who use e-Libra across campuses.">
    <Thead
      ><tr>
        <th class="text-left">User</th>
        <th>Campus</th>
        <th>Role</th>
        <th>Last active</th>
        <th>Status</th>
      </tr></Thead
    >
    <Tbody :loading="false" :columns="5" :data="users">
      <tr v-for="user in users" :key="user.email">
        <td class="text-left">
          <p class="font-semibold">{{ user.name }}</p>
          <p class="text-xs text-foreground-secondary">{{ user.email }}</p>
        </td>
        <Td :data="user.campus" /><Td :data="user.role" /><Td :data="user.lastActive" />
        <td>
          <Badge variant="success">{{ user.status }}</Badge>
        </td>
      </tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
const users = [
  { name: 'Sofia Garcia', email: 'sofia.garcia@isu.edu.ph', campus: 'Main Campus', role: 'System Admin', lastActive: 'Today, 9:42 AM', status: 'Active' },
  { name: 'Rafael Dela Cruz', email: 'rafael.delacruz@isu.edu.ph', campus: 'Main Campus', role: 'Librarian', lastActive: 'Today, 8:16 AM', status: 'Active' },
  { name: 'Bea Navarro', email: 'bea.navarro@isu.edu.ph', campus: 'Roxas Campus', role: 'Campus Admin', lastActive: 'Yesterday', status: 'Active' },
  { name: 'Noel Santos', email: 'noel.santos@isu.edu.ph', campus: 'Angadanan Campus', role: 'Librarian', lastActive: 'Aug 25, 2026', status: 'Invited' },
]
</script>
