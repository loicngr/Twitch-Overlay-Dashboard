import network from 'src/utils/network'
import { useMainStore } from 'stores/store'

export const logInApi = async (payload) => {
  const r = await network.post(`/login_check`, { ...payload })

  return r.data
}

export const refreshJWT = async () => {
  const mainStore = useMainStore()

  const refreshToken = mainStore.jwt?.refresh_token

  if (!refreshToken) {
    return false
  }

  const r = await network.post(`/token/refresh`, { refresh_token: refreshToken })

  if (r?.data?.token) {
    mainStore.updateJwt(r.data)
    return true
  }

  return false
}
