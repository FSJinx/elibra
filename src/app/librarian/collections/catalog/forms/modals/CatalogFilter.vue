<template>
  <Button class="max-w-max" left-icon="filter" @click="filterBtn?.open()">Filters</Button>

  <Modal ref="filterBtn">
    <ModalHeader use-default-layout title="Filter" icon="filter" />

    <ModalBody>
      <Form class="p-5">
        <template #body>
          <!-- ITEM TYPES -->
          <Control direction="col">
            <Label id="catalog-types">Item Type</Label>

            <Select id="catalog-types" title="Item Types" v-model="filter.item_type_id">
              <Option value="" selected disabled>Select Item Type</Option>
              <Option :value="type.id" v-for="type in options.item_types">{{ type.name }}</Option>
            </Select>
          </Control>

          <!-- CATEGORIES -->
          <Control direction="col">
            <Label id="catalog-category">Category</Label>

            <Select id="catalog-category" title="Categories" v-model="filter.item_type_category_id">
              <Option value="" selected disabled>{{ filter.item_type_id ? 'Select Category' : 'Please select an item type first' }}</Option>
              <Option v-for="category in options.categories?.filter((i) => i.item_type_id == filter.item_type_id)" :key="category.id" :value="category.id">{{ category.name }}</Option>
            </Select>
          </Control>

          <!-- SORT -->
          <Control direction="col">
            <Label id="catalog-sort">Sort</Label>

            <Select id="catalog-sort" title="Sort" v-model="filter.sort">
              <Option value="">Default</Option>
              <Option value="title">Title</Option>
              <Option value="call_number">Call Number</Option>
              <Option value="updated_at">Date Modified</Option>
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
        </template>
      </Form>
    </ModalBody>

    <ModalFooter>
      <div class="flex items-center justify-end gap-2">
        <Button variant="danger" @click="filterBtn?.close()">Cancel</Button>
        <Button variant="success" @click="applyFilter">Apply</Button>
      </div>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import type Modal from '@/components/my/Modal.vue'
import type { ItemParams } from '@/stores/catalog/item.store'

const options = optionStore()

const props = defineProps<{ params: ItemParams }>()

const filterBtn = ref<typeof Modal | null>(null)
const emit = defineEmits(['filterApplied'])

const filter = reactive(props.params)

function applyFilter() {
  emit('filterApplied', filter)
  filterBtn.value?.close()
}

watch(
  () => filter.item_type_id,
  () => {
    filter.item_type_category_id = ''
  },
  {
    deep: true,
  },
)
</script>

<style scoped></style>
