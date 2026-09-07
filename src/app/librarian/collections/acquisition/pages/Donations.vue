<template>
  <Card>
    <Form class="flex items-center gap-2">
      <Label id="search">Search</Label>
      <Input id="search" type="text" class="max-w-100" placeholder="Search donations by ID, donor name, or cause..." enable-clear />
      <Button type="submit" variant="success" class="mr-auto">Search</Button>

      <Select id="type-filter" title="Type" class="max-w-50">
        <Option value="">All Types</Option>
        <Option value="monetary">Monetary</Option>
        <Option value="in-kind">In-Kind (Items)</Option>
        <Option value="recurring">Recurring</Option>
      </Select>

      <Select id="status-filter" title="Status" class="max-w-50">
        <Option value="">All Statuses</Option>
        <Option value="completed">Completed</Option>
        <Option value="pending">Pending Receipt</Option>
        <Option value="pledged">Pledged</Option>
        <Option value="refunded">Refunded</Option>
      </Select>
      <Button variant="info">Record Donation</Button>
    </Form>
  </Card>

  <div class="flex-1 flex mt-3">
    <Table class="" title="Donations" subtitle="Track donor contributions, pledges, and itemized gifts.">
      <Thead>
        <tr>
          <Th>Donation ID</Th>
          <Th>Donor Name</Th>
          <Th>Type</Th>
          <Th>Campaign / Cause</Th>
          <Th>Amount / Items</Th>
          <Th>Payment Method</Th>
          <Th>Status</Th>
          <Th>Date Received</Th>
          <Th>Receipt</Th>
        </tr>
      </Thead>
      <Tbody :data="data" :loading="false" :cols="9">
        <tr v-for="(item, index) in data" :key="item.donationId">
          <Td class="font-medium">#{{ item.donationId }}</Td>
          <Td>
            <div class="font-medium">{{ item.donorName }}</div>
            <div class="text-xs text-gray-500">{{ item.donorType }}</div>
          </Td>
          <Td>{{ item.type }}</Td>
          <Td>{{ item.campaign }}</Td>
          <Td class="font-semibold">
            <span v-if="item.amount">${{ item.amount.toLocaleString() }}</span>
            <span v-else>{{ item.itemDescription }}</span>
          </Td>
          <Td>{{ item.paymentMethod }}</Td>
          <Td>
            <span
              :class="{
                'bg-green-100 text-green-800': item.status === 'Completed',
                'bg-yellow-100 text-yellow-800': item.status === 'Pending',
                'bg-blue-100 text-blue-800': item.status === 'Pledged',
                'bg-gray-100 text-gray-800': item.status === 'Refunded',
              }"
              class="px-2 py-1 rounded text-xs font-medium"
            >
              {{ item.status }}
            </span>
          </Td>
          <Td>{{ item.dateReceived }}</Td>
          <Td>
            <Button size="sm">View Receipt</Button>
          </Td>
        </tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Donation {
  donationId: string
  donorName: string
  donorType: 'Individual' | 'Corporate' | 'Foundation' | 'Anonymous'
  type: 'Monetary' | 'In-Kind' | 'Recurring'
  campaign: string
  amount?: number
  itemDescription?: string
  paymentMethod: string
  status: 'Completed' | 'Pending' | 'Pledged' | 'Refunded'
  dateReceived: string
}

const data = ref<Donation[]>([
  {
    donationId: 'DON-2026-041',
    donorName: 'Acme Foundation',
    donorType: 'Corporate',
    type: 'Monetary',
    campaign: 'Annual Tech Drive',
    amount: 10000.0,
    paymentMethod: 'Wire Transfer',
    status: 'Completed',
    dateReceived: '2026-03-01',
  },
  {
    donationId: 'DON-2026-040',
    donorName: 'Eleanor Vance',
    donorType: 'Individual',
    type: 'In-Kind',
    campaign: 'Community Outreach',
    itemDescription: '15 Laptop Computers',
    paymentMethod: 'N/A (Physical Item)',
    status: 'Pending',
    dateReceived: '2026-02-28',
  },
  {
    donationId: 'DON-2026-039',
    donorName: 'Marcus Aurelius',
    donorType: 'Individual',
    type: 'Recurring',
    campaign: 'General Operating Fund',
    amount: 250.0,
    paymentMethod: 'Credit Card',
    status: 'Completed',
    dateReceived: '2026-02-15',
  },
])
</script>

<style scoped></style>
