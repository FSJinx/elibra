<template>
  <div class="size-full flex flex-col sm:max-w-400 px-5 gap-3 mx-auto overflow-hidden">
    <SectionHeader title="AcaRepo" description="Browse your thesis submissions" icon=""></SectionHeader>

    <div class="flex-1 flex flex-col gap-3 overflow-y-auto">
      <NewSubmission />
      <Table title="My Submissions" subtitle="Browse your thesis submissions">
        <Thead>
          <tr>
            <Th class="text-left">Title</Th>
            <Th>Date Submitted</Th>
            <Th>Status</Th>
            <Th>Remarks</Th>
          </tr>
        </Thead>
        <Tbody :data="data" :loading="loading">
          <Tr v-for="(item, index) in data" :key="index">
            <Td class="text-left text-wrap" :data="item.title" />
            <Td :data="parse.formatDateAgo(item.created_at)" />
            <Td>
              <Status :variant="item.status">{{ item.status }}</Status>
            </Td>
            <Td class="max-w-xs">
              <template v-if="item.status === 'approved'">
                <p class="text-wrap" v-if="item.on_catalog">Your thesis is now on the official catalog. Everyone can now browse the manuscript through the OPAC.</p>
                <p class="text-wrap" v-else="item.on_catalog">Your thesis is approved and is pending to be released in the catalog.</p>
              </template>
              <template v-else>
                <p class="text-wrap">{{ item.statusRemark ?? 'Your submission has been rejected' }}</p>
              </template>
            </Td>
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewSubmission from '@/app/patron/acarepo/NewSubmission.vue'
import { AcaRepo } from '@/interfaces/acarepo'

const acarepo = acaRepoStore()
const parse = useParser()
const auth = authStore()

const data = ref<AcaRepo[] | null>(null)
const pagination = ref<Pagination>()
const loading = ref<boolean>(false)

async function fetch() {
  loading.value = true
  try {
    const res = await acarepo.fetch({ submitted_by: auth?.user?.patron?.id })
    data.value = res.data?.data
    pagination.value = res?.data
  } finally {
    loading.value = false
  }
}

onBeforeMount(fetch)
</script>

<style scoped></style>
