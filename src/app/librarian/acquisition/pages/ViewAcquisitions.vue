<template>
  <div class="size-full flex flex-col">
    <div class="flex items-center bg-background p-4 pb-0">
      <Button variant="text" @click="router.back()" icon="arrow-left">
        <span class="text-lg">Back to Acquisition Record</span>
      </Button>
    </div>
    <SectionHeader title="Purchase #" description="Some description here that describes the whole page" icon="receipt">
      <div class="flex items-end justify-end gap-2">
        <Button variant="primary" data-title="Add new item to this purchase">Add New Item</Button>
      </div>
    </SectionHeader>

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
        <Tbody :data="data" :loading="false" :cols="5">
          <tr v-for="(item, index) in data">
            <Td class="text-left" :data="item.title" />
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

const data = [
  {
    id: 1,
    title: 'Epson L3210 Tank Printer',
    quantity: 2,
    unitPrice: 8800.0,
    discount: 500.0,
    netPrice: 8300.0,
  },
  {
    id: 2,
    title: 'A4 Copier Paper (80gsm / 500 sheets)',
    quantity: 15,
    unitPrice: 250.0,
    discount: 0.0,
    netPrice: 250.0,
  },
  {
    id: 3,
    title: 'Logitech MK270 Wireless Keyboard & Mouse Set',
    quantity: 5,
    unitPrice: 1250.0,
    discount: 100.0,
    netPrice: 1150.0,
  },
  {
    id: 4,
    title: 'ASUS 24-inch Full HD IPS Monitor',
    quantity: 3,
    unitPrice: 6500.0,
    discount: 300.0,
    netPrice: 6200.0,
  },
  {
    id: 5,
    title: 'Heavy Duty Stapler with Staples Set',
    quantity: 4,
    unitPrice: 450.0,
    discount: 0.0,
    netPrice: 450.0,
  },
]
</script>

<style scoped></style>
