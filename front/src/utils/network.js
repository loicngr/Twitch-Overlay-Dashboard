import axios from 'axios'
import { useMainStore } from 'stores/store'
import { JWT_ERRORS, JWT_EXPIRED_MESSAGE, JWT_INVALID_MESSAGE, NETWORK_UNKNOWN } from 'src/utils/consts/apiErrors'
import { refreshJWT } from 'src/utils/api/logIn'
import { toast } from 'src/utils/index'

const network = axios.create({
  baseURL: import.meta.env.VITE_APP_BACK
})

network.interceptors.request.use((config) => {
  const mainStore = useMainStore()
  const t = mainStore.jwt

  if (t && t.token) {
    config.headers.Authorization = `Bearer ${t.token}`
  }

  if (import.meta.env.DEV && window.xdebug) {
    config.params = {
      ...config.params || {},
      XDEBUG_SESSION_START: 'PHPSTORM'
    }
  }

  return config
})

const handleApiError = async (error) => {
  if (!error) {
    return Promise.reject(new Error(NETWORK_UNKNOWN))
  }

  const { response } = error

  if (!response) {
    return Promise.reject(new Error(NETWORK_UNKNOWN))
  }

  const responseMessage = response?.data?.message

  switch (response.status) {
    case 401: {
      try {
        if ([JWT_INVALID_MESSAGE, JWT_EXPIRED_MESSAGE].indexOf(responseMessage) !== -1) {
          const status = await refreshJWT()

          if (!status) {
            return networkLogOut()
          }

          return network(error.config)
        }
      } catch (e) {}

      if (JWT_ERRORS.indexOf(responseMessage) === -1) {
        networkLogOut()
      }

      break
    }
    case 403: {
      networkLogOut()
      break
    }
    case 503: {
      toast.negative(`Server error`)
      break
    }
    default:
      break
  }
}

network.interceptors.response.use((response) => response, handleApiError)

const networkLogOut = () => {
  const mainStore = useMainStore()
  mainStore.logOutUser()
}

export default network
