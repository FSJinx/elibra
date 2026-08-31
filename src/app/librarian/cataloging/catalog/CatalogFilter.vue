<template>
  <Button class="max-w-max" left-icon="filter" @click="filterBtn?.open()">Filters</Button>

  <Modal ref="filterBtn">
    <template #header>
      <Icon icon="filter" />
      Filter
    </template>

    <Form class="p-5">
      <template #body>
        <!-- ITEM TYPES -->
        <Control direction="col">
          <Label id="catalog-types">Item Type</Label>

          <Select id="catalog-types" title="Item Types" v-model="filter.item_type">
            <Option value="">All Item Types</Option>
            <Option value="books">Books</Option>
            <Option value="serials">Serials</Option>
            <Option value="media">Media/DVDs</Option>
          </Select>
        </Control>

        <!-- CATEGORIES -->
        <Control direction="col">
          <Label id="catalog-category">Category</Label>

          <Select id="catalog-category" title="Categories" v-model="filter.category">
            <Option value="">All Categories</Option>
            <Option value="books">Books</Option>
            <Option value="periodicals">Periodicals</Option>
            <Option value="media">Media/DVDs</Option>
          </Select>
        </Control>

        <!-- SORT -->
        <Control direction="col">
          <Label id="catalog-sort">Sort</Label>

          <Select id="catalog-sort" title="Sort" v-model="filter.sort">
            <Option value="">Default</Option>
            <Option value="title">Title</Option>
            <Option value="item_type">Item Type</Option>
            <Option value="year_publication">Publication Year</Option>
            <Option value="status">Status</Option>
          </Select>
        </Control>

        <!-- ORDER -->
        <Control direction="col">
          <Label id="catalog-order">Order</Label>

          <Select id="catalog-order" title="Order" v-model="filter.order">
            <Option value="asc">Ascending</Option>
            <Option value="desc">Descending</Option>
          </Select>
        </Control>

        <!-- STATUS -->
        <Control direction="col">
          <Label id="catalog-status">Status</Label>

          <Select id="catalog-status" title="Status" v-model="filter.status">
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
        <Button>Reset</Button>
        <Button variant="danger" @click="filterBtn?.close()">Cancel</Button>
        <Button variant="success" @click="applyFilter">Apply</Button>
      </div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import type Modal from '@/components/my/Modal.vue'

const filterBtn = ref<typeof Modal | null>(null)
const emit = defineEmits(['filterApplied'])

const filter = reactive({
  item_type: '',
  category: '',
  sort: '',
  order: 'asc',
  status: '',
})

function applyFilter() {
  emit('filterApplied', filter)
  filterBtn.value?.close()
}
</script>

<style scoped></style>
