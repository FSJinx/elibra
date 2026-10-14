<template>
  <!-- <PageConstruction /> -->

  <div class="size-full flex flex-col">
    <SectionHeader title="Publisher" description="Manage the list of publishers" icon="shop" />

    <div class="flex-1 flex flex-col p-5 gap-5">
      <Card>
        <CardBody class="grid grid-cols-2 items-center gap-2">
          <div class="flex items-center gap-2">
            <Input id="" type="text" class="min-w-100" placeholder="Search for vendor by name..." />
            <Select title="Sort By">
              <Option value="" selected disabled>Select Sort</Option>
              <Option value="name">Name</Option>
              <Option value="created_at">Date Added</Option>
            </Select>
            <Select title="Order">
              <Option value="" selected disabled>Select Order</Option>
              <Option value="asc">Ascending</Option>
              <Option value="desc">Descending</Option>
            </Select>
          </div>

          <div class="ml-auto"> 
            <Button variant="primary" icon="plus-lg">New Publisher</Button>
          </div>
        </CardBody>
      </Card>

      <div class="flex-1 flex">
        <Table title="Publisher Table" subtitle="List of publishers">
          <Thead>
            <tr>
              <Th>No.</Th>
              <Th>Name</Th>
              <Th>Address</Th>
              <Th>Date Added</Th>
              <Th>Actions</Th>
            </tr>
          </Thead>
          <Tbody :data="vendors.data" :loading="vendors.loading" cols="5">
            <tr v-for="(vendor, index) in vendors.data" :key="index"> 
              <Td :data="index + 1"/>
              <Td :data="vendor.name"/>
              <Td :data="vendor.address"/>
              <Td :data="vendor.created_at"/>
              <Td></Td>
            </tr>
          </Tbody>
        </Table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const vendors = useVendorStore()
</script>

<style scoped></style>
