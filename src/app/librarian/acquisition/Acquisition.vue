<template>
  <div class="size-full flex flex-col">
    <SectionHeader class="border-b border-border" title="Acquisitions" description="Manage your library's acquisition record" icon="receipt">
      <div class="flex items-end justify-end">
        <AddNewAcquisitionButton />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3">
      <Card>
        <Form class="flex items-center justify-end gap-2">
          <Label id="search" class="mr-3">Search</Label>
          <Input id="search" type="text" class="max-w-100" placeholder="Search donations by ID, donor name, or cause..." enable-clear />

          <Select id="type-filter" title="Type" class="max-w-75">
            <Option value="">All Acquisition Modes</Option>
            <Option value="purchase">Purchase</Option>
            <Option value="donation">Donation</Option>
            <Option value="gift">Gift</Option>
          </Select>

          <Select id="status-filter" title="Status" class="max-w-50">
            <Option value="">All Statuses</Option>
            <Option value="completed">Completed</Option>
            <Option value="pending">Pending Receipt</Option>
            <Option value="pledged">Pledged</Option>
            <Option value="refunded">Refunded</Option>
          </Select>
          <Button type="submit" variant="info">Search</Button>
        </Form>
      </Card>
      <Table class="" title="Recent Acquisitions" subtitle="These are your library's recent acquisitions.">
        <Thead>
          <tr>
            <Th>Purchase ID</Th>
            <Th>Dealer</Th>
            <Th>Mode of Acquisition</Th>
            <Th>Date</Th>
            <Th>Remarks</Th>
            <Th>Date Added</Th>
            <Th>Date Updated</Th>
          </tr>
        </Thead>
        <Tbody :data="data" :loading="false" cols="7">
          <router-link v-for="a in data" :to="{ name: 'librarian.acquisition.view' }" custom v-slot="{ navigate }">
            <tr class="hover cursor-pointer" @click="navigate" role="button">
              <Td :data="a.purchaseId"></Td>
              <Td :data="a.dealer"></Td>
              <Td :data="a.modeOfAcquisition"></Td>
              <Td :data="a.date"></Td>
              <Td :data="a.remarks"></Td>
              <Td> {{ parse.timeAgo(a.dateAdded) }}</Td>
              <Td> {{ parse.timeAgo(a.dateUpdated) }}</Td>
            </tr>
          </router-link>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewAcquisitionButton from '@/app/librarian/acquisition/modals/AddNewAcquisitionButton.vue';
import SectionHeader from '@/components/my/SectionHeader.vue'

const parse = useParser()
const data = ref([
  {
    purchaseId: 'ISU-E-2026070101',
    dealer: 'National Book Store',
    modeOfAcquisition: 'Purchased',
    date: 'July 1, 2026',
    remarks: null,
    dateAdded: '2026-07-01 08:30:00',
    dateUpdated: '2026-07-01 08:34:00',
  },
  {
    purchaseId: 'ISU-E-2026070102',
    dealer: 'Silicon Valley',
    modeOfAcquisition: 'Donated',
    date: 'July 2, 2026',
    remarks: 'Complete set with accessories',
    dateAdded: '2026-07-02 10:15:00',
    dateUpdated: '2026-07-02 10:22:00',
  },
  {
    purchaseId: 'ISU-E-2026070103',
    dealer: 'Toyota Isabela',
    modeOfAcquisition: 'Purchased',
    date: 'July 5, 2026',
    remarks: null,
    dateAdded: '2026-07-05 13:45:00',
    dateUpdated: '2026-07-05 14:15:00',
  },
  {
    purchaseId: 'ISU-E-2026070104',
    dealer: 'Wilcon Depot',
    modeOfAcquisition: 'Procured',
    date: 'July 10, 2026',
    remarks: 'Awaiting delivery',
    dateAdded: '2026-07-10 09:00:00',
    dateUpdated: '2026-07-10 09:00:00',
  },
  {
    purchaseId: 'ISU-E-2026070105',
    dealer: 'Abenson Appliances',
    modeOfAcquisition: 'Purchased',
    date: 'July 12, 2026',
    remarks: null,
    dateAdded: '2026-07-12 16:20:00',
    dateUpdated: '2026-07-12 17:05:00',
  },
])
</script>

<style scoped></style>
