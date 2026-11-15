<template>
  <aside class="hidden xl:block w-100 p-5 bg-background border border-border rounded-xl overflow-y-auto">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-foreground-secondary">Filters</p>
        <h2 class="mt-1 text-xl font-semibold tracking-tight">Refine results</h2>
      </div>
    </div>

    <div class="h-px bg-border my-5"></div>
  </aside>

  <Button clas="bg-default" variant="text" left-icon="sliders" @click="open">Filter</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Filters" subtitle="Refine your quest for knowledge" icon="books" />

    <ModalBody class="p-5">
      <button type="button" class="text-xs text-foreground-secondary hover:text-danger transition-colors" @click="$emit('reset')">Reset</button>
      <Form id="opac-form" @submit="apply">
        <Control direction="col">
          <Label id="params-campus_id">Campus</Label>
          <Select id="params-campus_id" title="Campus" v-model="params.campus_id">
            <Option value="">All data</Option>
            <template v-for="item in options.campuses" :key="item.id">
              <Option :value="item.id">{{ item.name }}</Option>
            </template>
          </Select>
        </Control>

        <Control direction="col">
          <Label id="params-sort">Sort results</Label>
          <Select id="params-sort" title="Sort By" v-model="params.sort">
            <Option value="">Relevance</Option>
            <Option value="title">Title</Option>
            <Option value="publication_year">Publication year</Option>
          </Select>
        </Control>

        <Control direction="col">
          <Label id="params-order">Order</Label>
          <Select id="params-order" v-model="params.order" placeholder="Default">
            <Option value="asc">Ascending</Option>
            <Option value="desc">Descending</Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter>
      <Button class="w-full mt-3" variant="primary" @click="$emit('apply')"> Apply filters </Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const model = defineModel<Partial<OpacParams>>('params', { required: true })
const emit = defineEmits(['apply', 'reset'])

const options = optionStore()

const modal = ref<InstanceType<typeof Modal>>()
const params = reactive<Partial<OpacParams>>({})

function open() {
  Object.assign(params, model.value)
  modal.value?.open()
  close()
}

function close() {
  modal.value?.close()
}

function apply() {
  emit('apply', model.value)
  close()
}
</script>
