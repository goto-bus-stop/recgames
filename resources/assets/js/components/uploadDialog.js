import yo from 'yo-yo'
import uploadStatus from './uploadStatus'

export default function dialog ({
  uploads,
  onClose
}) {
  const ids = Object.keys(uploads)
  return yo`<div class="modal is-active">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Uploading ...</p>
        <button class="delete" onclick=${onClose}></button>
      </header>
      <section class="modal-card-body">
        ${ids.map((id) => uploadStatus(uploads[id]))}
      </section>
      <footer class="modal-card-foot">
        <a class="button">Cancel</a>
      </footer>
    </div>
  </div>`
}
