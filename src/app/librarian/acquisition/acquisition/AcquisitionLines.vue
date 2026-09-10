<template>
  <div class="size-full flex flex-col">
    <div class="flex items-center bg-background p-4 pb-0">
      <Button variant="text" @click="router.back()" icon="arrow-left">
        <span class="text-lg">Back to Acquisition Record</span>
      </Button>
    </div>
    <div class="grid grid-cols-2 p-5 bg-background border-b border-border">
      <div class="">
        <p class="font-semibold text-primary uppercase tracking-wider">{{ currentData?.acquisition_mode }}</p>
        <H5>Acquisiton ID#: {{ currentData?.acquisition_id }}</H5>
        <p>Date of Acquisition: {{ parse.formatDate(currentData?.acquisition_date) }}</p>
      </div>
      <div class="flex items-end justify-end gap-2">
        <Button variant="primary" data-title="Add new item to this purchase">Add New Item</Button>
      </div>
    </div>

    <div class="flex-1 flex p-5 -mt-2">
      <Table title="Acquired Items" subtitle="Items in this purchase">
        <Thead>
          <tr>
            <Th class="text-left">Title</Th>
            <Th>Quantity</Th>
            <Th>Unit Price</Th>
            <Th>Discount</Th>
            <Th>Net Price</Th>
          </tr>
        </Thead>
        <Tbody :data="lines.data" :loading="lines.loading" :cols="5">
          <tr class="hover" v-for="(item, index) in lines.data">
            <Td class="text-left">
              <p class="font-medium">{{ item.items?.title }}</p>
              <p class="text-xs text-muted-foreground">{{ item.items?.subtitle }}</p>
            </Td>
            <Td :data="item.quantity" />
            <Td :data="parse.toMoney(item.unitPrice)" />
            <Td :data="parse.toMoney(item.discount)" />
            <Td :data="parse.toMoney(item.netPrice)" />
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import SectionHeader from '@/components/my/SectionHeader.vue'

const parse = useParser()

const { currentData } = useAcquisitionStore()
const lines = useAcquisitionLinesStore()

console.log(currentData)
</script>

<style scoped></style>
