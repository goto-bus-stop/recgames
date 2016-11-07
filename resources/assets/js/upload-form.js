import { select } from './util'

export default function apply (uploadForm) {
  uploadForm.onsubmit = (event) => {
    event.preventDefault()
    select('#upload-button').forEach((button) => {
      button.classList.add('is-loading')
      button.disabled = true
    })

    var progress = select('#upload-progress')[0]
    progress.parentNode.classList.remove('is-hidden')

    progress.max = 100

    var formData = new FormData(uploadForm)

    var uploadUrl = window.recgames.upload
    var xhr = new XMLHttpRequest()
    xhr.open('POST', uploadUrl)
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhr.onload = onload
    xhr.upload.onprogress = onprogress

    xhr.send(formData)

    function onprogress (event) {
      progress.value = (event.loaded / event.total) * 100
    }
    function onload () {
      window.location = JSON.parse(xhr.responseText).redirectUrl
    }
  }
}
