<template>
  <q-form
    class="row justify-center"
    autofocus
    :style="{maxWidth: '500px'}"
    @submit.prevent="onSubmit"
  >
    <div class="row col-12 q-gutter-sm">
      <q-input
        v-model.trim="email"
        label="Email"
        type="email"
        class="col-12"
        filled
        outlined
        required
        clearable
      />
      <q-input
        v-model.trim="password"
        label="Password"
        type="password"
        class="col-12"
        filled
        outlined
        required
        clearable
      />
      <q-btn
        label="Submit"
        color="primary"
        class="col-12"
        type="submit"
      />
    </div>
  </q-form>
</template>

<script setup>
import { ref } from 'vue'

const emits = defineEmits(['submit'])
const email = ref(null)
const password = ref(null)

const onSubmit = () => {
  emits('submit', { email: email.value, password: password.value })
}
</script>
