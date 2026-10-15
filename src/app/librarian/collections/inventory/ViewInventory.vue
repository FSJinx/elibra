<template>
  <div class="size-full flex flex-col overflow-hidden">
    <!-- Top Header Navigation -->
    <header class="flex items-center gap-3 p-4 border-b border-border bg-container">
      <Button @click="router.back()" variant="text" icon="arrow-left" aria-label="Go back" />
      <Title :level="2" class="text-lg font-bold">
        Inventory ID: <span class="text-primary">{{ inventoryDetails.id }}</span>
      </Title>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col gap-3 p-3 overflow-y-auto">
      <!-- Inventory Meta Information -->
      <Card class="grid grid-cols-3">
        <Control col>
          <p class="text-xs text-muted-foreground uppercase tracking-wider font-medium">Section</p>
          <p class="text-base font-semibold text-foreground">{{ inventoryDetails.section }}</p>
        </Control>
        <Control col>
          <p class="text-xs text-muted-foreground uppercase tracking-wider font-medium">Initiated By</p>
          <p class="text-base font-semibold text-foreground">{{ inventoryDetails.initiatedBy }}</p>
        </Control>
        <Control col>
          <p class="text-xs text-muted-foreground uppercase tracking-wider font-medium">Status</p>
          <Status class="max-w-max" :variant="inventoryDetails.status">{{ inventoryDetails.status }}</Status>
        </Control>
      </Card>

      <Card class="">
        <Form class="flex-1 flex items-center gap-2" id="search-item" @submit="">
          <Input type="text" placeholder="Search by title" class="max-w-100" />
          <Button form="search-item" type="submit" variant="info">Search</Button>
        </Form>
      </Card>

      <!-- Items Table -->
      <Table title="Items" subtitle="Verify items in this inventory" :data-length="items.length">
        <Thead>
          <tr>
            <Th class="text-left">Title</Th>
            <Th class="text-center">Copies</Th>
            <Th class="text-right">Actions</Th>
          </tr>
        </Thead>
        <tbody>
          <tr v-for="item in items" :key="item.id" class="border-b border-border hover:bg-muted/30 transition-colors">
            <Td class="text-left font-medium">{{ item.title }}</Td>
            <Td>{{ item.copies }}</Td>
            <Td class="text-right">
              <Button size="sm" variant="info" @click="verifyItem(item.id)"> Verify </Button>
            </Td>
          </tr>
          <tr v-if="items.length === 0">
            <Td colspan="3" class="text-center py-8 text-muted-foreground"> No items found in this inventory. </Td>
          </tr>
        </tbody>
      </Table>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Sample reactive data (palitan base sa tunay mong data source/props)
const inventoryDetails = ref({
  id: 'INV-2026-001',
  section: 'General Sections',
  initiatedBy: 'Juan Dela Cruz',
  status: 'pending',
})

const items = ref([
  { id: 1, title: 'Introduction to Algorithms', copies: 5 },
  { id: 2, title: 'Clean Code: A Handbook', copies: 3 },
  { id: 3, title: 'Vue.js Up & Running', copies: 8 },
])

const verifyItem = (id: number) => {
  // Logic para sa pag-verify ng item
  console.log('Verifying item:', id)
}
</script>

<style scoped></style>
