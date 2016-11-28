import { applyMiddleware, combineReducers, createStore } from 'redux'
import { handleActions } from 'redux-actions'
import uploadMiddleware from './middleware/uploadGame'
import * as actions from './actions'

const set = (obj, prop, value) => {
  const clone = Object.assign({}, obj)
  clone[prop] = value
  return clone
}

const merge = (obj, merge) =>
  Object.assign({}, obj, merge)

const uploads = handleActions({
  UPLOAD_START: (state, { payload, meta }) =>
    set(state, meta.id, {
      filename: payload.filename,
      complete: 0,
      url: null
    }),
  UPLOAD_PROGRESS: (state, { payload, meta }) =>
    set(state, meta.id,
      set(state[meta.id], 'complete', payload.complete)
    ),
  UPLOAD_FINISH: (state, { payload, meta }) =>
    set(state, meta.id, merge(state[meta.id], {
      complete: 100,
      error: payload.error,
      url: payload.url
    }))
}, [])

const reducer = combineReducers({
  uploads
})

const store = createStore(reducer, {}, applyMiddleware(uploadMiddleware))

window._s = store

export default store
