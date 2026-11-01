<template>
  <h5 class="uppercase font-medium text-muted-foreground">Personal Information</h5>
  <div class="grid grid-cols-5 gap-3">
    <Control id="last_name" class="col-span-3" col required>
      <Label>Last Name</Label>
      <Input type="text" placeholder="e.g. Dela Cruz" v-model="form.last_name" :error="errors.last_name" auto-focus />
    </Control>

    <Control id="middle_initial" class="col-span-2" col nullable>
      <Label>Middle Initial</Label>
      <Input type="text" placeholder="e.g. A" v-model="form.middle_initial" :error="errors.middle_initial" />
    </Control>

    <Control id="first_name" class="col-span-4" col required>
      <Label>First Name</Label>
      <Input type="text" placeholder="e.g. Dela Cruz" v-model="form.first_name" :error="errors.first_name" />
    </Control>

    <Control id="sex" class="col-span-1" col required>
      <Label>Sex</Label>
      <Select v-model="form.sex" :error="errors.sex">
        <Option value="" selected disabled>Select a sex</Option>
        <Option value="male">Male</Option>
        <Option value="female">Female</Option>
      </Select>
    </Control>

    <Control id="patron_type" class="col-span-5" col required>
      <Label>Patron Type</Label>
      <Select title="Patron Types" v-model="form.patron_type_id" :error="errors.patron_type_id" helper="If you are not a student of ISU, please select 'Guest'.">
        <Option value="" selected disabled>Select Patron Type</Option>
        <Option :value="type.id" v-for="type in options.patron_types">{{ type.name }}</Option>
      </Select>
    </Control>

    <Control id="campus" class="col-span-5" col v-if="hasCampus" required>
      <Label>Campus</Label>
      <Select v-model="form.campus_id" :error="errors.campus_id">
        <Option value="" selected disabled>Select campus</Option>
        <Option :value="campus.id" v-for="campus in options.campuses">{{ campus.name }}</Option>
      </Select>
    </Control>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  form: Partial<User>
  errors: Partial<User>
}>()

const options = optionStore()

const hasCampus = computed(() => options.patron_types?.find((i) => i.id === props.form.patron_type_id)?.has_campus ?? false)
</script>
