<template>
  <div class="size-full flex flex-col gap-5 w-full max-w-7xl mx-auto">
    <SectionHeader :title="library.currentData?.name ?? 'Library'" :description="`Manage ${library.currentData?.name ?? 'this library'}'s information`">
      <div v-if="library.currentData" class="flex items-start justify-end gap-2">
        <EditLibraryModal :data="library.currentData" />
        <Button class="text-danger!" left-icon="trash" @click="remove">Delete</Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-5 p-5">
      <Card>
        <CardBody class="grid grid-cols-2 gap-5">
          <h1 class="col-span-2 uppercase font-semibold text-muted-foreground">Library Information</h1>
          <div>
            <p class="card-label">Name</p>
            <p class="card-data">{{ library.currentData?.name ?? '—' }}</p>
          </div>
          <div>
            <p class="card-label">Campus</p>
            <p class="card-data">{{ library.currentData?.campus?.name ?? '—' }}</p>
          </div>
          <div>
            <p class="card-label">Phone</p>
            <p class="card-data">{{ library.currentData?.contact_info || '—' }}</p>
          </div>
          <div>
            <p class="card-label">Email</p>
            <p class="card-data">{{ library.currentData?.email || '—' }}</p>
          </div>
          <div>
            <p class="card-label">Opening Hours</p>
            <p class="card-data">{{ library.currentData?.opening_hour || '—' }}</p>
          </div>
          <div>
            <p class="card-label">Closing Hours</p>
            <p class="card-data">{{ library.currentData?.closing_hour || '—' }}</p>
          </div>
        </CardBody>
      </Card>

      <div class="grid grid-cols-2 gap-5">
        <Card>
          <CardBody class="flex items-center gap-5">
            <span class="flex size-13 rounded-xl border border-success/15 bg-success-soft text-success">
              <Icon class="m-auto text-xl" icon="calendar-plus" />
            </span>
            <div class="space-y-1.5">
              <p class="card-label">Date Created</p>
              <p class="card-data">{{ parse.formatDate(library.currentData?.created_at) ?? '—' }}</p>
            </div>
          </CardBody>
        </Card>
        <Card>
          <CardBody class="flex items-center gap-5">
            <span class="flex size-13 rounded-xl border border-restore/15 bg-restore-soft text-restore">
              <Icon class="m-auto text-xl" icon="clock-history" />
            </span>
            <div class="space-y-1.5">
              <p class="card-label">Last Modified</p>
              <p class="card-data">{{ parse.formatDateAgo(library.currentData?.updated_at ?? null) ?? '—' }}</p>
            </div>
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
