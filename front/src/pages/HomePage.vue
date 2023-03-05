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

<script>
import { defineComponent } from 'vue'
import { getTwitchOauthUrl } from 'src/utils/twitch'
import { loadingHandler } from 'src/utils'

export default defineComponent({
  name: 'HomePage',

  setup () {
    const goTwitchOAuth = async () => loadingHandler(async () => {
      const url = await getTwitchOauthUrl()
      window.location.href = url
    })

    return {
      goTwitchOAuth
    }
  }
})
</script>
