<template>
  <div class="flex flex-col w-full max-w-7xl mx-auto gap-5">
    <Card class="flex items-start justify-between gap-5 p-6!">
      <div class="">
        <Title :level="1">{{ item?.title }}</Title>
        <p class="text-muted-foreground text-sm">
          <span class="italic">{{ item?.subtitle }}</span>
          <span class="italic" v-if="item?.subtitle && item.publication_year"> • </span>
          <span>{{ item?.publication_year }}</span>
        </p>
      </div>

      <div class="flex justify-end gap-2">
        <Button left-icon="pencil-square">Edit</Button>
      </div>
    </Card>

    <Card>
      <Title :level="2" class="text-primary mb-5">Basic Information</Title>

      <div class="grid grid-cols-2 gap-5">
        <Control direction="col">
          <label for="">Title</label>
          <p>{{ item?.title }}</p>
        </Control>
        <Control direction="col">
          <label for="">Subtitle</label>
          <p>{{ item?.title }}</p>
        </Control>
        <Control direction="col">
          <label for="">Call Number</label>
          <p>{{ item?.call_number }}</p>
        </Control>
        <Control direction="col">
          <label for="">Year of Publication</label>
          <p>{{ item?.call_number }}</p>
        </Control>
        <Control direction="col" class="col-span-2">
          <label for="">Description</label>
          <p>{{ item?.title }}</p>
        </Control>
      </div>
    </Card>

    <Card>
      <Title :level="2" class="text-primary mb-5">Classification</Title>

      <div class="grid grid-cols-2 gap-5">
        <Control direction="col">
          <label for="">Item type</label>
          <p>{{ item?.item_type?.name }}</p>
        </Control>
        <Control direction="col">
          <label for="">Category</label>
          <p>{{ item?.item_type_category?.name }}</p>
        </Control>
        <Control direction="col">
          <label for="">Language</label>
          <p>{{ item?.language?.name }}</p>
        </Control>
        <Control direction="col">
          <label for="">General Location</label>
          <p>{{ item?.branch?.name }}</p>
        </Control>
      </div>
    </Card>

    <Card v-if="item?.item_type?.slug === 'academic'">
      <Title :level="2" class="text-primary mb-5">Academic Information</Title>

      <div class="grid grid-cols-2 gap-5">spre
        <Control direction="col">
          <label for="">DOI</label>
          <p>
            <template v-if="item?.academic?.doi">
              <a :href="item?.academic?.doi" class="hover:text-primary underline">{{ item?.academic?.doi }}</a>
            </template>
            <template v-else>
              <span>Null</span>
            </template>
          </p>
        </Control>
        <Control direction="col">
          <label for="">Related Department</label>
          <p>{{ item?.department?.name ?? 'None' }}</p>
        </Control>
      </div>
    </Card>
  </div>
</template>

<script setup lang="ts">
const item = useItemStore().currentData
</script>

<style scoped>
label {
  color: var(--color-muted-foreground);
  font-size: smaller;
  font-weight: 500;
  /* letter-spacing: 0.015rem; */
  text-transform: uppercase;
}

.control p {
  font-weight: 500;
}
</style>
