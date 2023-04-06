import network from 'src/utils/network'

export const getManagers = async (id) => {
  const r = await network.get(`/managers/${id ?? ''}`)
  return r.data
}

export const getCurrentManager = async () => {
  const r = await network.get(`/me`)
  return r.data
}
