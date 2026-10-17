<template>
  <Card class="">
    <Form id="" class="flex items-center gap-2" @submit="">
      <Input id="" class="max-w-150" type="text" placeholder="Search by patron's ebc or id number or item's accession code or title" />
      <Button type="submit" variant="info">Search</Button>
    </Form>
  </Card>

  <Table title="Recent Check Outs" subtitle="These are the recent check outs of your library" data-length="">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th class="text-left">Title</Th>
        <Th>Accession</Th>
        <Th class="text-left">Borrower</Th>
        <Th>Borrowed On</Th>
        <Th>Due Date</Th>
        <Th>Status</Th>
        <Th>Actions</Th>
      </tr>
    </Thead>
  </Table>
</template>

<script setup lang="ts"></script>

<style scoped></style>
