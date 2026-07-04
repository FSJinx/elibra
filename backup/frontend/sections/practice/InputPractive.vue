<template>
  <div class="flex flex-col w-full">
    <h1 class="text-2xl text-center font-bold p-5 bg-gray-500 text-white w-full">Inputs</h1>
    <table class="table-fixed">
      <thead>
        <tr>
          <th>Name</th>
          <th>Component Sample</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Input: Text</td>
          <td class="flex flex-col border-0 gap-4">
            <BaseInput type="text" label="Username" name="username" placeholder="Enter your username here" size="small" variant="rounded" autocomplete="off" />
            <BaseInput type="text" label="Username" name="username" placeholder="Enter your username here" size="default" variant="rounded" />
            <BaseInput type="text" label="Username" name="username" placeholder="Enter your username here" size="large" variant="rounded" />
          </td>
        </tr>
        <tr>
          <td>Input: Password</td>
          <td>
            <BaseInput type="password" label="Password" name="password" placeholder="Enter your password here" size="default" variant="rounded" :required="true" />
          </td>
        </tr>
        <tr>
          <td>Input: Email</td>
          <td>
            <BaseInput type="email" label="Email" name="email" placeholder="Enter your email here" size="default" variant="rounded" />
          </td>
        </tr>
        <tr>
          <td>Input: Number</td>
          <td>
            <BaseInput type="number" label="Phone Number" name="number" placeholder="Enter your phone number here" size="default" variant="circle" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import BaseInput from '@/components/BaseInput.vue'
</script>
