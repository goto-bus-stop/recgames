import yo from 'yo-yo'
import store from './state'
import { uploadStart } from './actions'
import uploadDialog from './components/uploadDialog'

export default function apply (body) {
  body.classList.remove('nojs')
  body.classList.add('js')

  body.addEventListener('dragenter', (event) => {
    event.preventDefault()
  }, false)
  body.addEventListener('dragover', (event) => {
    event.preventDefault()
  }, false)
  body.addEventListener('drop', (event) => {
    event.preventDefault()

    for (const file of event.dataTransfer.files) {
      store.dispatch(uploadStart(file))
    }

    const dialog = uploadDialog({ uploads: {}, onClose: () => {} })
    document.body.appendChild(dialog)

    const subscription = store.subscribe(() => {
      yo.update(dialog, uploadDialog({
        uploads: store.getState().uploads,
        onClose: () => {
          document.body.removeChild(dialog)
          subscription()
        }
      }))
    })
  }, false)
}
