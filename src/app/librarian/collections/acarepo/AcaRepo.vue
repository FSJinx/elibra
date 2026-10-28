<template>
  <div class="flex flex-col size-full overflow-hidden">
    <!-- Header -->
    <SectionHeader title="AcaRepo" description="AcaRepo: Academic Repository" icon="mortarboard">
      <div class="flex items-center justify-end gap-2">
        <NewInventory />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <!-- <Card>
        <CardBody> </CardBody>
      </Card> -->

      <Table title="Submissions" subtitle="Track theses submitted for approval." :data="pagination">
        <Thead>
          <Th>No.</Th>
          <Th class="text-left">Title</Th>
          <Th>Category</Th>
          <Th>Submitted By</Th>
          <Th>Date Submitted</Th>
          <Th>Status</Th>
          <Th>Remarks</Th>
          <Th>Action</Th>
        </Thead>

        <Tbody cols="7" :loading="acarepo.loading" :data="acarepo.data">
          <tr v-for="(item, index) in acarepo.data" :key="item.id" class="border-b border-border last:border-0 hover:bg-secondary/30 transition-colors">
            <Td :data="index + 1"></Td>
            <Td class="text-left" :data="item.title"></Td>
            <Td :data="item.category === 'ut' ? 'Undergraduate' : 'Graduate'"></Td>
            <Td :data="item.submitter?.user?.name?.full"></Td>
            <Td :data="parse.formatTimeAgo(item.created_at)"></Td>
            <Td>
              <Status :variant="item.status">{{ item.status }}</Status>
            </Td>
            <Td :data="item.statusRemark"></Td>
            <Td class="space-x-2" v-if="item.status === 'pending'">
              <ReviewSubmission :data="item" @refresh="search" />
              <RejectSubmission :data="item" @refresh="search" />
            </Td>
            <Td v-if="item.status === 'approved'">
              <Button variant="primary" left-icon="plus-lg" size="sm">Add to Catalog</Button>
            </Td>
            <Td v-if="item.status === 'rejected'" :data="null" />
          </tr>

          <tr v-if="acarepo.data?.length === 0">
            <Td colspan="8" class="px-5 py-10 text-center text-sm">No items match your filters.</Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import RejectSubmission from '@/app/librarian/collections/acarepo/modals/RejectSubmission.vue'
import ReviewSubmission from '@/app/librarian/collections/acarepo/modals/ReviewSubmission.vue'

const parse = useParser()

const acarepo = acaRepoStore()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)

async function search() {
  loading.value = false
  try {
    const res = await acarepo.fetch()
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

onBeforeMount(search)
</script>
