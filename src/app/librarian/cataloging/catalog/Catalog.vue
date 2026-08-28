<template>
  <div class="flex flex-col size-full">
    <!-- Header & Action -->
    <div class="grid grid-cols-2 items-center px-3 py-5">
      <div class="">
        <H6>Catalog List</H6>
        <p class="text-sm text-foreground-secondary">65 records</p>
      </div>

      <div class="flex items-center justify-end gap-2">
        <Input id="catalog-query" placeholder="Search for an item in the catalog..." class="max-w-100" enable-clear />
        <CatalogFilter />
        <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.cataloging.add-new' }">Add New Item</Button>
      </div>
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

<script setup lang="ts">
import CatalogFilter from '@/app/librarian/cataloging/catalog/CatalogFilter.vue'
</script>

<style scoped></style>
