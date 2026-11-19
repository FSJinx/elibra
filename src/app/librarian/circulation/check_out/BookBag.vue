<template>
  <Button class="hover:text-primary" left-icon="bag" @click="open">Bag</Button>

  <Drawer ref="drawer">
    <DrawerHeader class="flex items-center">
      <Icon icon="bag" class="mr-3" />
      <span>Check Out Bag</span>
    </DrawerHeader>

    <DrawerBody>
      <div class="size-full text-muted-foreground text-center place-content-center" v-if="!model?.patron && !model?.accessions?.length">No items in the check out bag yet.</div>

      <div class="flex flex-col items-start gap-3" v-else>
        <div class="w-full flex items-center justify-between">
          <Title :level="2">Item Preview</Title>
          <Button size="sm" class="hover:text-danger" :disabled="submitting" @click="clear">Clear Bag</Button>
        </div>
        <Card class="w-full p-3!" v-for="group in groupedAccessions" :key="group.itemId">
          <CardBody class="grid grid-cols-[1fr_5fr] gap-3">
            <div class="max-w-15 max-h-max border border-border rounded-lg overflow-hidden">
              <img :src="group.accessions[0]?.item?.cover_media ? `${storageRoute}/${group.accessions[0]?.item?.cover_media}` : book" alt="" class="" />
            </div>

            <div class="flex-1 flex flex-col gap-2">
              <div class="flex items-center justify-between gap-2">
                <Title>{{ group.title }}</Title>
                <span class="shrink-0 rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                  Qty {{ group.accessions.length }}
                </span>
              </div>
              <div class="flex flex-col gap-2">
                <div v-for="copy in group.accessions" :key="copy.id" class="flex items-center gap-2 rounded-md border border-border px-3 py-2">
                  <div class="min-w-0 flex-1">
                    <p class="text-xs text-foreground-secondary">Accession Number</p>
                    <p class="truncate text-sm font-medium">{{ copy.accession_number ?? 'Invalid' }}</p>
                  </div>
                  <span class="text-xs capitalize text-foreground-secondary">{{ copy.status ?? 'Invalid' }}</span>
                  <Button class="hover:text-danger shrink-0" icon="trash" size="sm" :disabled="submitting" @click="remove(copy)" />
                </div>
              </div>
            </div>
          </CardBody>
        </Card>
      </div>
    </DrawerBody>

    <DrawerFooter class="flex flex-col gap-5" v-if="model?.accessions?.length || model?.patron">
      <div class="preview flex flex-col gap-2">
        <Title :level="3">Borrowing Information</Title>

        <div class="grid grid-cols-2 gap-1 text-sm">
          <p class="text-foreground-secondary line-clamp-1">Name:</p>
          <span class="text-foreground text-end">{{ model?.patron?.user?.name?.full ?? 'No selected patron' }}</span>

          <p class="text-foreground-secondary line-clamp-1">ID Number:</p>
          <span class="text-foreground text-end">{{ model?.patron?.user?.username ?? 'No selected patron' }}</span>

          <p class="text-foreground-secondary line-clamp-1">Total items borrowing:</p>
          <span class="text-foreground text-end">{{ model?.accessions?.length ?? 'No items selected' }}</span>
        </div>

        <Form class="w-full" id="submit-borrow" @submit="submit">
          <Control id="borrow-loan-mode" col>
            <Label>Loan Mode</Label>

            <Select v-model="model.loan_mode_id" required :disabled="submitting">
              <Option value="" selected disabled>Select Loan Mode</Option>
              <Option :value="item.id" v-for="item in options.loan_modes">{{ item.name }}</Option>
            </Select>
          </Control>
        </Form>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <Button variant="danger" :disabled="submitting" @click="close">Cancel</Button>
        <Button variant="success" form="submit-borrow" type="submit" :loading="submitting" :disabled="!canProceed">Proceed</Button>
      </div>
    </DrawerFooter>
  </Drawer>
</template>

<script setup lang="ts">
import Drawer from '@/components/drawer/Drawer.vue'
import book from '@/assets/images/default_book.png'

const model = defineModel<CheckOut>()
const emit = defineEmits(['clear'])
defineExpose({ close, open })

const pop = usePopup()
const options = optionStore()
const circulation = circulationStore()

const drawer = ref<InstanceType<typeof Drawer>>()
const submitting = ref(false)
const canProceed = computed(() =>
  Boolean(model.value?.patron && model.value.accessions?.length && model.value.loan_mode_id && !submitting.value),
)

const groupedAccessions = computed(() => {
  const groups = new Map<string, { itemId: string; title: string; accessions: Accession[] }>()

  for (const copy of model.value?.accessions ?? []) {
    const itemId = String(copy.item?.id ?? copy.item_id ?? copy.id)
    const title = copy.item?.title ?? 'Unknown item'
    const group = groups.get(itemId)

    if (group) {
      group.accessions.push(copy)
    } else {
      groups.set(itemId, { itemId, title, accessions: [copy] })
    }
  }

  return [...groups.values()]
})

function open() {
  drawer.value?.open()
}

function close() {
  drawer.value?.close()
}

async function submit() {
  const patron = model.value?.patron
  const accessions = model.value?.accessions ?? []
  const loanModeId = Number(model.value?.loan_mode_id)

  if (!patron || accessions.length === 0 || !Number.isInteger(loanModeId) || loanModeId <= 0) {
    await pop.info('Select a patron, at least one item, and a loan mode before proceeding.')
    return
  }

  const confirm = await pop.confirm({ text: 'Proceed checkout?' })

  if (confirm.isConfirmed) {
    submitting.value = true
    const checkedOutIds: Accession['id'][] = []
    pop.load()

    try {
      const res = await circulation.checkOut(model.value)
      pop.success(res.message)
      nextTick(() => {
        emit('clear')
        close()
      })
    } catch (e: any) {
      if (checkedOutIds.length > 0 && model.value?.accessions) {
        model.value.accessions = model.value.accessions.filter((item) => !checkedOutIds.includes(item.id))
      }

      pop.unload()
      const validationError = e?.response?.data?.errors
        ? Object.values(e.response.data.errors).flat()[0]
        : null
      const message = validationError ?? e?.response?.data?.message ?? 'Unable to complete checkout.'
      const partialMessage = checkedOutIds.length > 0
        ? `${checkedOutIds.length} item(s) were checked out; the remaining items are still in the bag. `
        : ''
      pop.error(`${partialMessage}${message}`)
    } finally {
      submitting.value = false
    }
  }
}

async function clear() {
  const confirm = await pop.confirm({ text: 'Are you sure you want to clear the current bag?' })

  if (confirm.isConfirmed) {
    emit('clear')
    close()
  }
}

async function remove(item: Accession) {
  const confirm = await pop.confirm({ text: `Are you sure you want to remove ${item.item?.title} from the bag?` })

  if (confirm.isConfirmed && model.value?.accessions) {
    model.value.accessions = model.value?.accessions?.filter((i) => i.id !== item.id)
    pop.success('Removed successfully!')
  }
}

onBeforeMount(async () => {
  try {
    await options.fetch('loan_modes')
  } catch (e: any) {
    await pop.error(e?.response?.data?.message ?? 'Unable to load loan modes.')
  }
})
</script>

<style scoped></style>
