<template>
  <q-page
    class="row flex-center"
  >
    <div
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
  </q-page>
</template>

<script setup>
import { getTwitchOauthUrl } from 'src/utils/twitch'
import { loadingHandler } from 'src/utils'

const goTwitchOAuth = async () => loadingHandler(async () => {
  window.location.href = await getTwitchOauthUrl()
})
</script>
