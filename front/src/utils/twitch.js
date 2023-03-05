import { OAUTH_TWITCH_BASE_URL } from 'src/utils/consts/twitch'
import { getState } from 'src/utils/api/oauthTwitch'

export const getTwitchOauthUrl = async () => {
  if (!process.env.TWITCH_OAUTH_CLIENT_ID || _.isEmpty(process.env.TWITCH_OAUTH_CLIENT_ID)) {
    throw new Error('Twitch client id not found.')
  }

  if (!process.env.TWITCH_OAUTH_REDIRECT_URL || _.isEmpty(process.env.TWITCH_OAUTH_REDIRECT_URL)) {
    throw new Error('Twitch redirect url not found.')
  }

  const state = await getState()

  if (!state) {
    throw new Error('State not found.')
  }

  let authURL = `${OAUTH_TWITCH_BASE_URL}/authorize`
  authURL += `?response_type=code`
  authURL += `&client_id=${process.env.TWITCH_OAUTH_CLIENT_ID}`
  authURL += `&redirect_uri=${process.env.TWITCH_OAUTH_REDIRECT_URL}`
  authURL += `&state=${state}`
  authURL += `&scope=${encodeURIComponent((process.env.TWITCH_OAUTH_SCOPES ?? '').split(',').join(' '))}`

  console.log(authURL)
  return authURL
}

export function extractTokenFromUrl (url) {
  const rawAccessToken = url.substring(url.indexOf('access_token=') + 13)
  return rawAccessToken.substring(0, rawAccessToken.indexOf('&'))
}

export const openTwitchOauth = () => {
  const url = getTwitchOauthUrl()
  if (!url) {
    throw new Error('URL not valid')
  }

  window.open(url)
}
