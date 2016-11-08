import yo from 'yo-yo'
import { select } from './util'
import uploadStatus from './components/uploadStatus'

function tryParse (json) {
  try {
    return JSON.parse(json)
  } catch (e) {}
}

export default function apply (uploadForm) {
  const createUrl = window.recgames.api.recordedGames.create

  uploadForm.onsubmit = (event) => {
    event.preventDefault()
    select('#upload-button').forEach((button) => {
      button.classList.add('is-hidden')
      button.disabled = true
    })

    const input = select('#upload-file', uploadForm)[0]

    for (const file of input.files) {
      const formData = new FormData()
      formData.append('recorded_game', file)

      const progress = uploadStatus({
        filename: file.name,
        complete: 0
      })
      uploadForm.appendChild(progress)

      fetch(createUrl, {
        method: 'post',
        headers: {
          accept: 'application/json'
        }
      })
        .then((response) => response.json())
        .then(({ data }) => {
          const xhr = new XMLHttpRequest()
          xhr.open('POST', data.links.upload)
          xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
          xhr.setRequestHeader('Accept', 'application/json')
          xhr.onload = () => {
            const result = tryParse(xhr.responseText)
            if (xhr.status === 200) {
              yo.update(progress, uploadStatus({
                filename: file.name,
                complete: 100,
                url: result.links.page
              }))
            } else {
              const props = result ? {
                error: result.errors.map((err) => err.title).join(' '),
                url: result.links ? result.links.page : null
              } : {
                error: 'Unknown error.'
              }
              yo.update(progress, uploadStatus(
                Object.assign({
                  filename: file.name,
                  complete: 100
                }, props)
              ))
            }
          }
          xhr.upload.onprogress = (event) => {
            yo.update(progress, uploadStatus({
              filename: file.name,
              complete: (event.loaded / event.total) * 100
            }))
          }

          xhr.send(formData)
        })
    }
  }
}
