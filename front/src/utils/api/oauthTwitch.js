import network from 'src/utils/network'

export const getState = async () => {
  const r = await network.get(`/oauth/state`)
  return r.data?.state
}
