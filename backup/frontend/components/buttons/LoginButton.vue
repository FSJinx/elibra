<template>
  <Button variant="outline-solid" color="primary" label="Login" @click="login?.open()" />

  <LoginModal ref="login" />
</template>

<script setup>
import { ref } from 'vue'
import LoginModal from '../modals/LoginModal.vue'
import Button from './Button.vue'

const login = ref(null)
</script>

<style scoped></style>
