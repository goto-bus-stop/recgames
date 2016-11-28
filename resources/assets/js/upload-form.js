import yo from 'yo-yo'
import { select } from './util'
import uploadStatus from './components/uploadStatus'
import store from './state'
import { uploadStart } from './actions'

function tryParse (json) {
  try {
    return JSON.parse(json)
  } catch (e) {}
}

function uploadForm ({ uploads }) {
  const ids = Object.keys(uploads)
  return yo`<div>${ids.map((id) => uploadStatus(uploads[id]))}</div>`
}

export default function apply (element) {
  const createUrl = window.recgames.api.recordedGames.create

  const uploading = uploadForm(store.getState())

  element.onsubmit = (event) => {
    event.preventDefault()
    select('#upload-button').forEach((button) => {
      button.classList.add('is-hidden')
      button.disabled = true
    })

    const input = select('#upload-file', element)[0]

    for (const file of input.files) {
      store.dispatch(uploadStart(file))
    }
  }

  element.appendChild(uploading)
  store.subscribe(() => {
    yo.update(uploading, uploadForm(store.getState()))
  })
}
