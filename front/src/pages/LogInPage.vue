<template>
  <q-page
    class="row flex-center"
  >
    <log-in @submit="tryLogIn" />
  </q-page>
</template>

<script setup>
import LogIn from 'components/Form/LogIn.vue'
import { logInApi } from 'src/utils/api/logIn'
import { loadingHandler } from 'src/utils'
import { useMainStore } from 'stores/store'

const mainStore = useMainStore()

const tryLogIn = async (value) => loadingHandler(async () => {
  const response = await logInApi(value)

  if (response && response.token && response.refresh_token) {
    mainStore.updateJwt(response)
  }
})
</script>
