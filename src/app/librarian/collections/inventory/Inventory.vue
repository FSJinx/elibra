<template>
  <div class="flex flex-col size-full overflow-hidden">
    <!-- Header -->
    <SectionHeader title="Stock Verification" description="Browse and verify materials on-shelf" icon="boxes">
      <div class="flex items-center justify-end gap-2">
        <NewInventory />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <Card>
        <CardBody> </CardBody>
      </Card>

      <Table title="Recent Inventory Tracking" subtitle="Track your inventory history here." :data-length="inventory.data?.length">
        <Thead>
          <Th>No.</Th>
          <Th>Inventory ID</Th>
          <Th>Shelf Location</Th>
          <Th>Created By</Th>
          <Th>Date Created</Th>
          <Th>Status</Th>
          <Th>Action</Th>
        </Thead>

        <Tbody cols="7" :loading="inventory.loading" :data="inventory.data">
          <tr v-for="item in inventory.data" :key="item.id" class="border-b border-border last:border-0 hover:bg-secondary/30 transition-colors">
            <Td :data="item.inventory_id"></Td>
            <Td :data="item.librarian_id"></Td>
            <Td :data="item.created_at"></Td>
            <Td :data="item.is_completed"></Td>
          </tr>

          <tr v-if="inventory.data?.length === 0">
            <Td colspan="8" class="px-5 py-10 text-center text-sm">No items match your filters.</Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewInventory from '@/app/librarian/collections/inventory/modals/NewInventory.vue'

const inventory = useInventoryStore()
</script>
