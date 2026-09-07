<template>
  <Card>
    <Form class="flex items-center gap-2">
      <Label id="">Search</Label>
      <Input id="" type="text" class="max-w-100" placeholder="Search requests by title, ID, or requester" enable-clear />
      <Button type="submit" variant="success" class="mr-auto">Search</Button>

      <Select id="" title="Status" class="max-w-75">
        <Option value="">All Statuses</Option>
      </Select>
      <Button variant="info">Add New Request</Button>
    </Form>
  </Card>

  <div class="flex-1 flex mt-3">
    <Table class="" title="Acquisition Requests" subtitle="View a list of all the acquisition requests.">
      <Thead>
        <tr>
          <Th>Request ID</Th>
          <Th>Title / Item</Th>
          <Th>Requested By</Th>
          <Th>Department</Th>
          <Th>Est. Cost</Th>
          <Th>Status</Th>
          <Th>Date</Th>
        </tr>
      </Thead>
      <Tbody :data="data" :loading="false" :cols="7">
        <tr v-for="(item, index) in data">
          <Th></Th>
        </tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
const data = ref([])
</script>

<style scoped></style>
