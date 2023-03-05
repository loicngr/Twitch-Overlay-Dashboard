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

<script setup>
import { computed } from 'vue'
import { useMainStore } from 'stores/store'
import LogInPage from 'pages/LogInPage.vue'

const mainStore = useMainStore()

const appName = import.meta.env.VITE_APP_NAME ?? '???'
const isLoggedIn = computed(() => mainStore.isLoggedIn)
</script>
