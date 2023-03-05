<template>
  <router-view />
</template>

<script setup>
import { refreshJWT } from 'src/utils/api/logIn'
import { loadingHandler } from 'src/utils'
import { useMainStore } from 'stores/store'

const mainStore = useMainStore()

loadingHandler(async () => {
  const status = await refreshJWT()

  if (!status) {
    mainStore.logOutUser()
  }
}, { message: 'login' })
</script>
