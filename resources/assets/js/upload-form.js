import { h, render } from 'preact'
import { select } from './util'
import Connect from './components/Connect'
import UploadStatus from './components/UploadStatus'
import store from './state'
import { uploadStart } from './actions'

function tryParse (json) {
  try {
    return JSON.parse(json)
  } catch (e) {}
}

const UploadForm = ({ uploads }) => (
  <div>
    {Object.keys(uploads).map((id) => (
      <UploadStatus {...uploads[id]} />
    ))}
  </div>
)

export default function apply (element) {
  const uploadWrapper = document.createElement('div')

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

  element.appendChild(uploadWrapper)

  render((
    <Connect store={store} component={UploadForm} />
  ), uploadWrapper)
}
