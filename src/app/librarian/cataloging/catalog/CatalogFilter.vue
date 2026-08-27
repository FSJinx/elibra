<template>
  <Button left-icon="filter" @click="filterBtn?.open()">Filters</Button>

  <Modal ref="filterBtn">
    <template #header>
      <Icon icon="filter" />
      Filter
    </template>
    <Form class="p-5">
      <template #body>
        <Control direction="col">
          <Label id="catalog-types">Item Type</Label>
          <Select id="catalog-types" title="Item Types">
            <Option value="">All Item Types</Option>
            <Option value="books">Books</Option>
            <Option value="serials">Serials</Option>
            <Option value="media">Media/DVDs</Option>
          </Select>
        </Control>
        <Control direction="col">
          <Label id="catalog-category">Category</Label>
          <Select id="catalog-category" title="Categories">
            <Option value="">All Categories</Option>
            <Option value="books">Books</Option>
            <Option value="periodicals">Periodicals</Option>
            <Option value="media">Media/DVDs</Option>
          </Select>
        </Control>
        <Control direction="col">
          <Label id="catalog-status">Status</Label>
          <Select id="catalog-status" title="Status">
            <Option value="">Status</Option>
            <Option value="available">Available</Option>
            <Option value="borrowed">Borrowed</Option>
            <Option value="reserved">Reserved</Option>
          </Select>
        </Control>
      </template>
    </Form>

    <template #footer>
      <div class="flex items-center justify-end gap-2 w-full">
        <Button variant="danger" @click="filterBtn?.close()">Cancel</Button>
        <Button variant="success">Apply</Button>
      </div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import type Modal from '@/components/my/Modal.vue'

const filterBtn = ref<typeof Modal | null>(null)
</script>

<style scoped></style>
