<template>
  <q-page
    class="row flex-center"
  >
    <div
      v-if="!isTwitchAccountLinked"
      :style="{maxWidth: '500px'}"
      class="col-xs-10 col-sm-6 text-center"
    >
      <q-btn
        label="Twitch authorization"
        class="q-my-md full-width"
        color="primary"
        @click="goTwitchOAuth"
      />
    </div>
    <div v-else>
      <h5>Welcome {{ me.email }}</h5>
      <q-btn
        label="Logout"
        class="full-width"
        color="red"
        @click="logout"
      />
    </div>
  </q-page>
</template>

<script setup>
import { getTwitchOauthUrl } from 'src/utils/twitch'
import { dialogConfirm, loadingHandler } from 'src/utils'
import { computed } from 'vue'
import { useMainStore } from 'stores/store'

const goTwitchOAuth = async () => loadingHandler(async () => {
  window.location.href = await getTwitchOauthUrl()
})

const mainStore = useMainStore()

const isTwitchAccountLinked = computed(() => mainStore.isTwitchAccountLinked)
const me = computed(() => mainStore.getMe)

const logout = () => dialogConfirm('Êtes-vous sûr de vouloir vous déconnecter ?')
  .onOk(() => {
    mainStore.logOutUser()
  })
</script>
