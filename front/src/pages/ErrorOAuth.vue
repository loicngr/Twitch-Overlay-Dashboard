<template>
  <div class="fullscreen bg-blue text-white text-center q-pa-md flex flex-center">
    <div>
      <div style="font-size: 30vh">
        Sorry
      </div>

      <div
        class="text-h2"
        style="opacity:.4"
      >
        {{ errorMessage }}
      </div>

      <q-btn
        class="q-mt-xl"
        color="white"
        text-color="blue"
        unelevated
        to="/"
        label="Go Home"
        no-caps
      />
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()
const errorMessage = route.query?.message ?? 'Oops. Process failed'
</script>
