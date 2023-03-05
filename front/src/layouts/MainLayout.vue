<template>
  <q-layout
    v-if="isLoggedIn"
    view="lHh Lpr lFf"
  >
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="fas fa-bars"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          {{ appName }}
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
  <q-layout
    v-else
    view="lHh Lpr lFf"
  >
    <q-page-container>
      <log-in-page />
    </q-page-container>
  </q-layout>
</template>

<script>
import { computed, defineComponent, ref } from 'vue'
import { useMainStore } from 'stores/store'
import LogInPage from 'pages/LogInPage.vue'

export default defineComponent({
  name: 'MainLayout',
  components: { LogInPage },

  setup () {
    const leftDrawerOpen = ref(false)
    const mainStore = useMainStore()

    const isLoggedIn = computed(() => mainStore.isLoggedIn)

    return {
      appName: import.meta.env.VITE_APP_NAME ?? '???',
      isLoggedIn,
      leftDrawerOpen, // todo
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
})
</script>
