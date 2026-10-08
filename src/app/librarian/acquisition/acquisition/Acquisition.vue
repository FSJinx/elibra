<template>
  <div class="size-full flex flex-col">
    <SectionHeader title="Acquisitions" description="Manage your library's acquisition record" icon="receipt">
      <div class="flex items-center justify-end">
        <AddNewAcquisitionButton />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3 overflow-hidden">
      <Card class="">
        <CardBody class="flex items-center gap-2">
          <Input id="search" type="text" class="max-w-100" placeholder="Search by dealer..." enable-clear />
          <Button type="submit" variant="info">Search</Button>
          <Select title="Sort" class="max-w-max">
            <Option value="">Sort By</Option>
          </Select>
          <Select title="Acquisition Mode" class="max-w-max">
            <Option value="" selected disabled>Select acquisition mode</Option>
            <Option value="purchased">Purchased</Option>
          </Select>
        </CardBody>
      </Card>
      <Table class="" title="Recent Acquisitions" subtitle="These are your library's recent acquisitions.">
        <Thead>
          <tr>
            <Th>Acquisition ID</Th>
            <Th class="text-left">Dealer</Th>
            <Th>Mode of Acquisition</Th>
            <Th>Date Acquired</Th>
            <Th>Date Added</Th>
            <Th>Last Updated</Th>
          </tr>
        </Thead>
        <Tbody :data="acquisition.data" :loading="acquisition.loading" cols="6">
          <router-link v-for="a in acquisition.data" :to="{ name: 'librarian.acquisition.lines', params: { id: a.id } }" custom v-slot="{ navigate }">
            <tr class="hover cursor-pointer" @click="navigate" role="button">
              <Td :data="a.acquisition_id"></Td>
              <Td class="text-left" :data="a.dealer"></Td>
              <Td class="capitalize" :data="a.acquisition_mode"></Td>
              <Td :data="parse.formatDate(a.acquisition_date)"></Td>
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
