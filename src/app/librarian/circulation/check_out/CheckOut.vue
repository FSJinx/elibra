<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Check Out" description="Lend items to patrons." icon="bag-plus">
      <div class="flex items-center justify-end gap-2">
        <Chip removable @remove="removePatron" v-if="form.patron">Current Borrower: {{ form.patron?.user?.name?.full }} - {{ form.patron?.user?.username }}</Chip>
        <BookBag ref="bookBag" v-model="form" @clear="clear" />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3 overflow-y-auto">
      <CheckPatron v-model="form.patron" v-if="!form.patron" />
      <CheckItems v-model="form.accessions" v-if="form.patron" />
    </div>
  </div>
</template>

<script setup lang="ts">
import BookBag from '@/app/librarian/circulation/check_out/BookBag.vue'
import CheckItems from '@/app/librarian/circulation/check_out/CheckItems.vue'
import CheckPatron from '@/app/librarian/circulation/check_out/CheckPatron.vue'

const pop = usePopup()

const bookBag = ref<InstanceType<typeof BookBag>>()

const borrowInfo = (): CheckOut => ({
  patron: null,
  accessions: null,
  loan_mode_id: ''
})

const form = ref<CheckOut>(borrowInfo())

async function clear() {
  const confirm = await pop.confirm({ text: 'Are you sure you want to clear the current bag?' })
  
  if (confirm.isConfirmed) {
    form.value = borrowInfo()
    bookBag.value?.close()
  }
}

async function removePatron() {
  const confirm = await pop.confirm({ text: `Are you sure you want to remove ${form.value?.patron?.user?.username} as the current borrower?` })

  if (confirm.isConfirmed) {
    form.value.patron = null
  }
}

watch(
  () => form.value.accessions,
  (val) => bookBag.value?.open(),
)
</script>

<style scoped></style>
