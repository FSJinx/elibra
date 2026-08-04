<template>
  <div class="flex items-center justify-end gap-2 w-full mb-4">
    <Input id="campus-query" left-icon="search" class="max-w-85" placeholder="Search by name, code, address" />

    <Button variant="primary">Create New</Button>
  </div>

  <Table>
    <Thead>
      <tr>
        <th class="text-left">Campus Name</th>
        <th>Code</th>
        <th>Address</th>
      </tr>
    </Thead>

    <Tbody>
      <tr v-for="i in 20" :key="i">
        <td class="text-left">Campus Branch {{ i }}</td>
        <td>CB-00{{ i }}</td>
        <td>123 University Avenue, Metro City</td>
      </tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts"></script>

<style scoped></style>
