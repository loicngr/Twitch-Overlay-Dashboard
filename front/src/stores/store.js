import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'
import { STORE_CONFIG, STORE_JWT } from 'src/utils/consts/store'

export const useMainStore = defineStore({
  id: 'main',

  state: () => ({
    config: useStorage('config', { ...STORE_CONFIG }),
    jwt: useStorage('jwt', { ...STORE_JWT })
  }),

  getters: {
    /**
     * Return store global config
     * @return {object}
     */
    getConfig: (state) => state.config,

    /**
     * Return store global jwt
     * @return {object}
     */
    getJwt: (state) => state.jwt,

    /**
     * Is user logged in
     * @return {boolean}
     */
    isLoggedIn: (state) => state.jwt.token !== null
  },

  actions: {
    updateJwt (jwt) {
      this.jwt = jwt
    },

    logOutUser () {
      this.jwt = { ...STORE_JWT }
    },

    updateConfig (oConfig) {
      const mainStore = useMainStore()

      this.config = {
        ...mainStore.getConfig,
        ...oConfig
      }
    }
  }
})
