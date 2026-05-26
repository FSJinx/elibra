<template>
  <router-view />
  <modal-backdrop />
  <popup-backdrop />
</template>

<script setup>
import ModalBackdrop from './components/commons/ModalBackdrop.vue'
import PopupBackdrop from './components/commons/PopupBackdrop.vue'
</script>

<style scoped></style>
