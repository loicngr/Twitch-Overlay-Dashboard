<template>
  <router-view />
</template>

<script setup>
import { computed, watch } from 'vue'
import { refreshJWT } from 'src/utils/api/logIn'
import { loadingHandler } from 'src/utils'
import { useMainStore } from 'stores/store'
import { getCurrentManager } from 'src/utils/api/manager'

const mainStore = useMainStore()

loadingHandler(async () => {
  const status = await refreshJWT()

  if (!status) {
    mainStore.logOutUser()
  }
}, { message: 'login' })

const isLoggedIn = computed(() => mainStore.isLoggedIn)

watch(isLoggedIn, (value) => {
  if (!value) {
    return
  }

  loadingHandler(async () => {
    const manager = await getCurrentManager()
    mainStore.updateMe(manager)
  }, { message: 'Init' })
}, { immediate: true })
</script>
