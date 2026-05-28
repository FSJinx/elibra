<template>
  <div class="flex flex-col w-full">
    <!-- Buttons Section -->
    <div class="flex flex-col gap-3 w-full border-b border-gray-200 p-5">
      <h1 class="text-2xl font-bold">Buttons</h1>
      <div class="grid grid-cols-2">
        <p class="my-auto">Soft Buttons</p>
        <div class="flex flex-wrap items-center gap-2">
          <Button label="View" icon="Eye" color="blue" type="soft" />
          <Button label="Add" icon="Plus" color="purple" type="soft" />
          <Button label="Submit" icon="Send" color="green" type="soft" />
          <Button label="Delete" icon="Trash" color="red" type="soft" />
          <Button label="Edit" icon="Pencil" color="yellow" type="soft" />
          <Button label="Add" icon="Plus" color="default" type="soft" />
        </div>
      </div>
      <div class="grid grid-cols-2">
        <p class="my-auto">Button Solid</p>
        <div class="flex flex-wrap items-center gap-2">
          <Button label="View" icon="Eye" color="blue" type="solid" />
          <Button label="Add" icon="Plus" color="purple" type="solid" />
          <Button label="Submit" icon="Send" color="green" type="solid" />
          <Button label="Delete" icon="Trash" color="red" type="solid" />
          <Button label="Edit" icon="Pencil" color="yellow" type="solid" />
          <Button label="Add" icon="Plus" color="default" type="solid" />
        </div>
      </div>
      <div class="grid grid-cols-2">
        <p class="my-auto">Button Outline</p>
        <div class="flex flex-wrap items-center gap-2">
          <Button label="View" icon="Eye" color="blue" type="outline" />
          <Button label="Add" icon="Plus" color="purple" type="outline" />
          <Button label="Submit" icon="Send" color="green" type="outline" />
          <Button label="Delete" icon="Trash" color="red" type="outline" />
          <Button label="Edit" icon="Pencil" color="yellow" type="outline" />
          <Button label="Add" icon="Plus" color="default" type="outline" />
        </div>
      </div>
      <div class="grid grid-cols-2">
        <p class="my-auto">Anchor Button</p>
        <div class="flex flex-wrap items-center gap-2">
          <AnchorButton label="View Admin" color="blue" type="outline" link="Admin" />
          <AnchorButton label="View Librarian" color="purple" type="outline" />
          <AnchorButton label="View Patron" color="default" type="outline" />
        </div>
      </div>
    </div>

    <!-- Modals Sections -->
    <div class="flex flex-col gap-3 w-full border-b border-gray-200 p-5">
      <h1 class="text-2xl font-bold">Popup Alerts</h1>
      <div class="grid grid-cols-2">
        <p class="my-auto">Confirm Popup</p>
        <div class="flex flex-wrap items-center gap-2">
          <Button label="Confirm" color="green" type="soft" @click="elpop.confirm('You successfully deleted your feelings.')" />
        </div>
      </div>
      <div class="grid grid-cols-2">
        <p class="my-auto">Loading Popup</p>
        <div class="flex flex-wrap items-center gap-2">
          <Button label="Loading" color="blue" type="soft" @click="elpop.load()" />
        </div>
      </div>
      <div class="grid grid-cols-2">
        <p class="my-auto">Success Popup</p>
        <div class="flex flex-wrap items-center gap-2">
          <Button label="Success" color="green" type="soft" @click="elpop.showSuccess('You successfully deleted your feelings.')" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import AnchorButton from '@/components/buttons/AnchorButton.vue'
import Button from '@/components/buttons/Button.vue'
import elpop from '@/plugins/elpop'
</script>
