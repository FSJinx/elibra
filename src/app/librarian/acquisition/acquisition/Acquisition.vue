<template>
  <div class="size-full flex flex-col">
    <SectionHeader class="border-b border-border" title="Acquisitions" description="Manage your library's acquisition record" icon="receipt">
      <div class="flex items-end justify-end">
        <AddNewAcquisitionButton />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3 overflow-hidden">
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
            <Th>Acquisition ID</Th>
            <Th class="text-left">Dealer</Th>
            <Th>Mode of Acquisition</Th>
            <Th>Date Acquired</Th>
            <Th>Remarks</Th>
            <Th>Date Added</Th>
            <Th>Last Updated</Th>
          </tr>
        </Thead>
        <Tbody :data="acquisition.data" :loading="acquisition.loading" cols="7">
          <router-link v-for="a in acquisition.data" :to="{ name: 'librarian.acquisition.lines', params: { id: a.id } }" custom v-slot="{ navigate }">
            <tr class="hover cursor-pointer" @click="navigate" role="button">
              <Td :data="a.acquisition_id"></Td>
              <Td class="text-left" :data="a.dealer"></Td>
              <Td :data="a.acquisition_mode"></Td>
              <Td :data="parse.formatDate(a.acquisition_date)"></Td>
              <Td :data="a.remarks"></Td>
              <Td :data="parse.formatTimeAgo(a.created_at ?? null)" :data-title="parse.formatDate(a.created_at ?? null)" />
              <Td :data="parse.formatTimeAgo(a.updated_at ?? null)" :data-title="parse.formatDate(a.updated_at ?? null)" />
            </tr>
          </router-link>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewAcquisitionButton from '@/app/librarian/acquisition/acquisition/modals/AddNewAcquisitionButton.vue'
import SectionHeader from '@/components/my/SectionHeader.vue'

const parse = useParser()
const acquisition = useAcquisitionStore()
</script>

<style scoped></style>
