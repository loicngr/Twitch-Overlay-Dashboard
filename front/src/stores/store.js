import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'
import { STORE_CONFIG, STORE_JWT, STORE_MANAGER } from 'src/utils/consts/store'

export const useMainStore = defineStore({
  id: 'main',

  state: () => ({
    config: useStorage('config', _.cloneDeep(STORE_CONFIG)),
    jwt: useStorage('jwt', _.cloneDeep(STORE_JWT)),
    me: useStorage('me', _.cloneDeep(STORE_MANAGER))
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
     * Return current manager logged.
     * @return {object}
     */
    getMe: (state) => state.me,

    /**
     * Return current manager logged.
     * @return {object}
     */
    isMe: (state) => state.me?.email !== null,

    /**
     * Is user logged in
     * @return {boolean}
     */
    isLoggedIn: (state) => state.jwt.token !== null,

    /**
     * Manager hav linked twitch account
     * @return {boolean}
     */
    isTwitchAccountLinked: (state) => !!(state.me?.managerSettingsFeature?.twitchOAuth?.accessToken ?? null)
  },

  actions: {
    updateJwt (jwt) {
      this.jwt = _.cloneDeep(jwt)
    },

    logOutUser () {
      this.jwt = _.cloneDeep(STORE_JWT)
    },

    updateMe (manager) {
      this.me = _.cloneDeep(manager)
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
