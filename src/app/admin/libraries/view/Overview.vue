<template>
  <div class="size-full flex flex-col gap-5 w-full max-w-7xl mx-auto">
    <div class="flex-1 flex flex-col gap-5 p-5">
      <div class="flex items-center gap-2">
        <div class="mr-auto space-y-1">
          <Title :level="2">{{ library.currentData?.name }}</Title>
          <p class="text-foreground-secondary text-sm space-x-5">
            <Icon icon="envelope" />
            {{ library.currentData?.email }}
          </p>
        </div>
        <EditLibraryModal :data="library.currentData" />
        <Button size="sm" class="hover:text-danger">Delete</Button>
      </div>
      <Card>
        <CardHeader>Library Information</CardHeader>
        <CardBody class="grid grid-cols-2 gap-5 text-sm">
          <Dl>
            <Dt>Name</Dt>
            <Dd>{{ library.currentData?.name ?? 'No name' }}</Dd>
          </Dl>
          <Dl>
            <Dt>Campus</Dt>
            <Dd>{{ library.currentData?.campus?.name ?? 'No name' }}</Dd>
          </Dl>
          <Dl>
            <Dt>Phone</Dt>
            <Dd>{{ library.currentData?.phone || '—' }}</Dd>
          </Dl>
          <Dl>
            <Dt>Email</Dt>
            <Dd>{{ library.currentData?.email || '—' }}</Dd>
          </Dl>
          <Dl>
            <Dt>Opening Hours</Dt>
            <Dd>{{ library.currentData?.opening_hour || '—' }}</Dd>
          </Dl>
          <Dl>
            <Dt>Closing Hours</Dt>
            <Dd>{{ library.currentData?.closing_hour || '—' }}</Dd>
          </Dl>
        </CardBody>
      </Card>

      <div class="grid grid-cols-2 gap-5 text-sm">
        <Card>
          <CardBody class="flex items-center gap-5">
            <span class="flex size-13 rounded-xl border border-success/15 bg-success-soft text-success">
              <Icon class="m-auto text-xl" icon="calendar-plus" />
            </span>
            <Dl>
              <Dt>Date Created</Dt>
              <Dd>{{ parse.formatDate(library.currentData?.created_at) ?? '—' }}</Dd>
            </Dl>
          </CardBody>
        </Card>
        <Card>
          <CardBody class="flex items-center gap-5">
            <span class="flex size-13 rounded-xl border border-restore/15 bg-restore-soft text-restore">
              <Icon class="m-auto text-xl" icon="clock-history" />
            </span>
            <Dl>
              <Dt>Last Modified</Dt>
              <Dd>{{ parse.formatDateAgo(library.currentData?.updated_at ?? null) ?? '—' }}</Dd>
            </Dl>
          </CardBody>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import EditLibraryModal from '@/app/admin/libraries/modals/EditLibraryModal.vue'

const library = libraryStore()
const parse = useParser()
const pop = usePopup()

async function remove() {
  const currentLibrary = library.currentData
  if (!currentLibrary) return

  const confirmation = await pop.confirm({ text: `Are you sure you want to delete ${currentLibrary.name}?` })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const res = await library.remove(currentLibrary)
    pop.unload()
    await pop.success(res.message ?? 'Library deleted successfully')
    await router.replace({ name: 'admin.libraries' })
  } catch (error: any) {
    pop.unload()
    await pop.error(error?.response?.data?.message ?? 'Unable to delete the library. Please try again.')
  }
}
</script>

<style scoped>
.card-label {
  margin-bottom: 0.25rem;
  color: var(--color-muted-foreground);
  font-size: 0.75rem;
  font-weight: 500;
  letter-spacing: 0.025rem;
  text-transform: uppercase;
}

.card-data {
  font-weight: 500;
}
</style>
