<template>
  <Button class="bg-default" variant="text" left-icon="sliders" @click="open">Filter</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Filters" subtitle="Refine your quest for knowledge" icon="sliders" />

    <ModalBody class="p-5">
      <Form class="grid grid-cols-2 gap-3" id="opac-form" @submit="apply">
        <Control col class="col-span-2">
          <Label>Campus</Label>
          <Select title="Campus" v-model="params.campus_id">
            <Option value="">All data</Option>
            <template v-for="item in options.campuses" :key="item.id">
              <Option :value="item.id">{{ item.name }}</Option>
            </template>
          </Select>
        </Control>

        <Control id="opac-item-type" col>
          <Label>Item Type</Label>
          <Select v-model="params.item_type_id">
            <Option value="">All Item Type</Option>
            <Option :value="item.id" v-for="item in options.item_types">{{ item.name }}</Option>
          </Select>
        </Control>

        <Control id="opac-item-category" col>
          <Label>Item Category </Label>
          <Select v-model="params.item_type_category_id">
            <Option value="">All Categories</Option>
            <Option :value="item.id" v-for="item in options.categories?.filter((i) => i.item_type_id === params.item_type_id)">{{ item.name }}</Option>
          </Select>
        </Control>

        <Control class="col-span-2" col>
          <Label>Sort By</Label>
          <Select title="Sort By" v-model="params.sort">
            <Option value="">Relevance</Option>
            <Option value="title">Title</Option>
            <Option value="publication_year">Publication year</Option>
          </Select>
        </Control>

        <Control class="col-span-2" col>
          <Label>Order</Label>
          <Select v-model="params.order" placeholder="Default">
            <Option value="asc">Ascending</Option>
            <Option value="desc">Descending</Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button @click="emit('reset')">Reset</Button>
      <Button variant="primary" @click="apply"> Apply filters </Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const model = defineModel<Partial<OpacParams>>('params', { required: true })
const emit = defineEmits(['apply', 'reset'])

const options = optionStore()

const modal = ref<InstanceType<typeof Modal>>()
const params = reactive<Partial<OpacParams>>(model.value)

function open() {
  // Object.assign(params, model.value)
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

function apply() {
  emit('apply', params)
  close()
}

onMounted(async () => await options.load(['item_types', 'campuses', 'categories']))
</script>
