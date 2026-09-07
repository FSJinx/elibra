<template>
  <Card>
    <Form class="flex items-center gap-2">
      <Label id="search">Search</Label>
      <Input id="search" type="text" class="max-w-125" placeholder="Search purchases by PO #, item, vendor, or buyer..." enable-clear />
      <Button type="submit" variant="success" class="mr-auto">Search</Button>

      <Select id="vendor-filter" title="Vendor" class="max-w-50">
        <Option value="">All Vendors</Option>
        <Option value="tech-corp">TechCorp Solutions</Option>
        <Option value="office-depot">Office Supplies Co</Option>
      </Select>

      <Select id="status-filter" title="Status" class="max-w-50">
        <Option value="">All Statuses</Option>
        <Option value="draft">Draft</Option>
        <Option value="ordered">Ordered</Option>
        <Option value="partial">Partially Received</Option>
        <Option value="received">Received</Option>
        <Option value="cancelled">Cancelled</Option>
      </Select>
      <Button variant="info">Create Purchase Order</Button>
    </Form>
  </Card>

  <div class="flex-1 flex mt-3">
    <Table class="" title="Purchase Orders" subtitle="Manage and track company purchase orders and inventory intake.">
      <Thead>
        <tr>
          <Th>PO Number</Th>
          <Th>Vendor</Th>
          <Th>Items / Description</Th>
          <Th>Buyer</Th>
          <Th>Total Amount</Th>
          <Th>Payment Terms</Th>
          <Th>Fulfillment Status</Th>
          <Th>Order Date</Th>
          <Th>Actions</Th>
        </tr>
      </Thead>
      <Tbody :data="data" :loading="false" :cols="9">
        <tr v-for="(item, index) in data" :key="item.poNumber">
          <Td class="font-medium">#{{ item.poNumber }}</Td>
          <Td>{{ item.vendorName }}</Td>
          <Td>
            <div>{{ item.itemSummary }}</div>
            <div class="text-xs text-gray-500">{{ item.totalItems }} items</div>
          </Td>
          <Td>{{ item.buyer }}</Td>
          <Td class="font-semibold">${{ item.totalAmount.toLocaleString() }}</Td>
          <Td>{{ item.paymentTerms }}</Td>
          <Td>
            <span
              :class="{
                'bg-gray-100 text-gray-800': item.status === 'Draft',
                'bg-blue-100 text-blue-800': item.status === 'Ordered',
                'bg-yellow-100 text-yellow-800': item.status === 'Partially Received',
                'bg-green-100 text-green-800': item.status === 'Received',
                'bg-red-100 text-red-800': item.status === 'Cancelled',
              }"
              class="px-2 py-1 rounded text-xs font-medium"
            >
              {{ item.status }}
            </span>
          </Td>
          <Td>{{ item.orderDate }}</Td>
          <Td>
            <Button size="sm">View Order</Button>
          </Td>
        </tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface PurchaseOrder {
  poNumber: string
  vendorName: string
  itemSummary: string
  totalItems: number
  buyer: string
  totalAmount: number
  paymentTerms: string
  status: 'Draft' | 'Ordered' | 'Partially Received' | 'Received' | 'Cancelled'
  orderDate: string
}

const data = ref<PurchaseOrder[]>([
  {
    poNumber: 'PO-2026-089',
    vendorName: 'Global Tech Distribution',
    itemSummary: 'Dell UltraSharp 27" Monitors',
    totalItems: 10,
    buyer: 'Alex Chen',
    totalAmount: 4500.0,
    paymentTerms: 'Net 30',
    status: 'Ordered',
    orderDate: '2026-03-02',
  },
  {
    poNumber: 'PO-2026-088',
    vendorName: 'Apex Office Supplies',
    itemSummary: 'Ergonomic Chairs & Desk Riser',
    totalItems: 4,
    buyer: 'Sarah Jenkins',
    totalAmount: 1280.5,
    paymentTerms: 'Net 15',
    status: 'Partially Received',
    orderDate: '2026-02-27',
  },
  {
    poNumber: 'PO-2026-087',
    vendorName: 'Logitech Direct',
    itemSummary: 'MX Master 3S Mice & Keyboards',
    totalItems: 15,
    buyer: 'Michael Brown',
    totalAmount: 2100.0,
    paymentTerms: 'Due on Receipt',
    status: 'Received',
    orderDate: '2026-02-20',
  },
])
</script>

<style scoped></style>
