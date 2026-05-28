<template>
  <router-view />
  <modal-backdrop />
  <elpop />
</template>

<script setup>
import Elpop from './components/alerts/Elpop.vue'
import ModalBackdrop from './components/commons/ModalBackdrop.vue'
</script>

<style scoped></style>
