import { createAction } from 'redux-actions'

let id = 0
export const uploadStart = createAction('UPLOAD_START', (file) => ({
  file,
  filename: file.name
}), () => ({
  id: id++
}))

export const uploadProgress = createAction('UPLOAD_PROGRESS',
  (id, payload) => payload,
  (id) => ({ id })
)
export const uploadFinish = createAction('UPLOAD_FINISH',
  (id, payload) => payload,
  (id) => ({ id })
)
