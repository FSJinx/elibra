<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Loans" description="Manage your library's loans and overdues" icon="bag-plus" />

    <div class="flex-1 flex flex-col p-5 gap-3">
      <Card class="">
        <Form class="flex-1 flex items-center gap-2" id="search-form" @submit="">
          <Input id="" class="max-w-125" type="text" placeholder="Search by patron name, item's accession code or title" />
          <Button form="search-form" type="submit" variant="info">Search</Button>
        </Form>
      </Card>

      <Table title="Loan List" subtitle="Browse your library's recent loans" data-length="">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession</Th>
            <Th>Item</Th>
            <Th>Patron</Th>
            <Th>Date Loaned</Th>
            <Th>Due Date</Th>
            <Th>Issued By</Th>
          </tr>
        </Thead>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped></style>
