<template>
  <div class="size-full flex flex-col overflow-hidden">
    <!-- Top Header Navigation -->
    <header class="flex items-center gap-3 p-4 border-b border-border bg-container">
      <Button @click="router.back()" variant="text" icon="arrow-left" aria-label="Go back" />
      <Title :level="2" class="text-lg font-bold">
        Inventory ID: <span class="text-primary">{{ inventory.currentData?.inventory_code }}</span>
      </Title>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col gap-3 p-3 overflow-y-auto">
      <!-- Inventory Meta Information -->
      <Card class="grid grid-cols-3">
        <Control col>
          <p class="text-xs text-muted-foreground uppercase tracking-wider font-medium">Section</p>
          <p class="text-base font-semibold text-foreground">{{ inventory.currentData?.section?.name }}</p>
        </Control>
        <Control col>
          <p class="text-xs text-muted-foreground uppercase tracking-wider font-medium">Initiated By</p>
          <p class="text-base font-semibold text-foreground">{{ inventory.currentData?.librarian?.user?.name?.full }}</p>
        </Control>
        <Control col>
          <p class="text-xs text-muted-foreground uppercase tracking-wider font-medium">Status</p>
          <Status class="max-w-max" :variant="inventory.currentData?.status">{{ inventory.currentData?.status }}</Status>
        </Control>
      </Card>

      <Card class="">
        <Form class="flex-1 flex items-center gap-2" id="search-item" @submit="">
          <Input type="text" placeholder="Search by title" class="max-w-100" />
          <Button form="search-item" type="submit" variant="info">Search</Button>
        </Form>
      </Card>

      <!-- Items Table -->
      <Table title="Items" subtitle="Verify items in this inventory">
        <Thead>
          <tr>
            <Th class="text-left">Title</Th>
            <Th class="text-center">Copies</Th>
            <Th class="text-right">Actions</Th>
          </tr>
        </Thead>
        <!-- <tbody>
          <tr v-for="item in []" :key="item.id" class="border-b border-border hover:bg-muted/30 transition-colors">
            <Td class="text-left font-medium">{{ item.title }}</Td>
            <Td>{{ item.copies }}</Td>
            <Td class="text-right">
              <Button size="sm" variant="info" @click="verifyItem(item.id)"> Verify </Button>
            </Td>
          </tr>
          <tr v-if="items.length === 0">
            <Td colspan="3" class="text-center py-8 text-muted-foreground"> No items found in this inventory. </Td>
          </tr>
        </tbody> -->
      </Table>
    </main>
  </div>
</template>

<script setup lang="ts">
import { popScopeId, ref } from 'vue'
import { useRouter } from 'vue-router'

const route = useRoute()
const pop = usePopup()
const inventory = inventoryStore()

async function load() {
  pop.load()
  try {
    await inventory.show(route.params.id)
  } finally {
    pop.unload()
  }
}
onBeforeMount(load)
</script>

<style scoped></style>
