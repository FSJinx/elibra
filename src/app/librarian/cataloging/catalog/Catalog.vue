<template>
  <div class="flex flex-col size-full">
    <!-- Header & Action -->
    <div class="flex items-center justify-end gap-2 p-5">
      <Input id="catalog-query" placeholder="Search for an item in the catalog..." class="max-w-100" enable-clear />
      <Select id="catalog-category" title="Categories" class="max-w-max">
        <Option value="">All Categories</Option>
        <Option value="books">Books</Option>
        <Option value="periodicals">Periodicals</Option>
        <Option value="media">Media/DVDs</Option>
      </Select>
      <Select id="catalog-status" title="Status" class="max-w-max">
        <Option value="">Status</Option>
        <Option value="available">Available</Option>
        <Option value="borrowed">Borrowed</Option>
        <Option value="reserved">Reserved</Option>
      </Select>
      <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.cataloging.add-new' }">Add New Item</Button>
    </div>

    <!-- Catalog Table -->
    <Table>
      <Thead>
        <tr>
          <Th class="text-left">Title</Th>
          <Th>Author</Th>
          <Th>ISBN/ISSN</Th>
          <Th>Call Number</Th>
          <Th>Status</Th>
        </tr>
      </Thead>
      <Tbody :columns="5" :loading="false" :data="1">
        <tr>
          <Td class="text-left">Spider-man: Brand New Day</Td>
          <Td>Bruce Lee</Td>
          <Td>978-0061120084</Td>
          <Td>120.283a 21</Td>
          <Td> <Status variant="success">Available</Status> </Td>
        </tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped></style>
