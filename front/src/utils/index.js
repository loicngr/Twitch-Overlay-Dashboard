import { Dialog, Notify, Loading } from 'quasar'

/**
 * @type {{
 * positive: function (opt: {}),
 * negative: function (opt: {}),
 * warning: function (opt: {}),
 * info: function (opt: {}),
 * }}
 */
export const toast = {}
;[
  'positive',
  'negative',
  'warning',
  'info'
].forEach((color) => {
  toast[color] = (opt = {}) => {
    const _opt = typeof opt === 'string'
      ? { message: opt }
      : opt
    return Notify.create({
      color,
      ..._opt
    })
  }
})

/**
 * @param f
 * @param {{message?: string, reject?: boolean}} opt
 * @returns {Promise<void>}
 */
export const loadingHandler = async (f, opt = { message: 'Loading' }) => {
  Loading.show({
    message: 'Loading',
    ...opt
  })
  try {
    return await f()
  } catch (e) {
    console.error(e)

    toast.negative({
      message: 'Error',
      caption: typeof e === 'string'
        ? e
        : e.message
    })

    return opt.reject
      ? Promise.reject(e)
      : undefined
  } finally {
    Loading.hide()
  }
}

/**
 * @param opt
 * @returns {DialogChainObject}
 */
export const dialogConfirm = (opt = {}) => {
  const _opt = typeof opt === 'string'
    ? { message: opt }
    : opt
  return Dialog.create({
    persistent: true,
    cancel: true,
    title: 'Confirm',
    ..._opt
  })
}

export const CONFIRM_REJECT_CANCEL = new Error('cancel')
export const CONFIRM_REJECT_DISMISS = new Error('dismiss')

/**
 * @param opt
 * @returns {Promise<unknown>}
 */
export const promiseConfirm = (opt) => new Promise((resolve, reject) => {
  dialogConfirm(opt)
    .onOk(resolve)
    .onCancel(() => reject(CONFIRM_REJECT_CANCEL))
    .onDismiss(() => reject(CONFIRM_REJECT_DISMISS))
})

/**
 * @param f
 * @returns {Promise<void>}
 */
export const tryCatchConfirm = async (f) => {
  try {
    return await f()
  } catch (e) {
    if ([CONFIRM_REJECT_CANCEL, CONFIRM_REJECT_DISMISS].indexOf(e) === -1) {
      return Promise.reject(e)
    }
  }
}

export const uid = () => {
  const a = new Uint32Array(3)
  window.crypto.getRandomValues(a)

  const strNow = performance.now().toString(36)
  const random = Array
    .from(a)
    .map(A => A.toString(36))
    .join('')
    .replace(/\./g, '')

  return `${strNow}${random}`
}
