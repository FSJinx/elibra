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
          <Input id="search" type="text" class="max-w-100" placeholder="Search by dealer..." enable-clear v-model="params.query" />
          <Button type="submit">Search</Button>
          <Select title="Acquisition Mode" class="max-w-max ml-auto" v-model="params.acquisition_mode">
            <Option value="" selected disabled>Select acquisition mode</Option>
            <Option value="donation">Donation</Option>
            <Option value="exchange">Exchange</Option>
            <Option value="gift">Gift</Option>
            <Option value="purchase">Purchase</Option>
          </Select>
          <Select title="Sort By" class="max-w-max" v-model="params.sort">
            <Option value="">Default</Option>
            <Option value="reference">Reference</Option>
            <Option value="acquisition_date">Acquisition Date</Option>
            <Option value="updated_at">Last Modified</Option>
          </Select>
          <Select title="Order" class="max-w-max" v-model="params.order">
            <Option value="asc">Ascending</Option>
            <Option value="desc">Descending</Option>
          </Select>
        </CardBody>
      </Card>
      <Table class="" title="Recent Acquisitions" subtitle="These are your library's recent acquisitions." :data="pagination" @per_page="params.per_page = $event">
        <Thead>
          <tr>
            <Th>Reference No.</Th>
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
              <Td :data="a.reference"></Td>
              <Td class="text-left" :data="a.dealer"></Td>
              <Td class="capitalize" :data="a.acquisition_mode"></Td>
              <Td :data="parse.formatDate(a.acquisition_date)"></Td>
              <Td :data="parse.formatTimeAgo(a.created_at ?? null)" :data-title="parse.formatDate(a.created_at ?? null)" />
              <Td :data="parse.formatTimeAgo(a.updated_at ?? null)" :data-title="parse.formatDate(a.updated_at ?? null)" />
            </tr>
          </router-link>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewAcquisitionButton from '@/app/librarian/acquisition/acquisition/modals/AddNewAcquisitionButton.vue'
import SectionHeader from '@/components/my/SectionHeader.vue'

const parse = useParser()
const pop = usePopup()
const acquisition = useAcquisitionStore()

const params = reactive(acquisition.params)
const pagination = ref<Pagination>()

async function search() {
  try {
    const res = await acquisition.fetch(params)
    pagination.value = res.data
  } catch (e: any) {
    throw e
  }
}

watchDebounced(() => params, search, { deep: true, immediate: true, debounce: 300 })
</script>

<style scoped></style>
