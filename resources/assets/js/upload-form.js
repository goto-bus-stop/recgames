import cx from 'classnames'
import yo from 'yo-yo'
import { select } from './util'

function uploadStatus ({
  filename,
  complete = 0,
  error = null,
  url = null
}) {
  const button = url
    ? yo`<a class="button is-primary" href=${url} target="_blank">View</a>`
    : yo`<a class="button is-primary is-loading" href="#">View</a>`
  let control
  if (url) {
    control = yo`<span class="is-success-text">Upload complete.</span>`
  } else if (error) {
    control = yo`<span class="is-danger-text">${error}</span>`
  } else {
    control = yo`<progress class="progress" value=${complete} max="100"></progress>`
  }
  return yo`<div class="box">
    <div class="columns">
      <div class="column">
        <label class="label">
          ${filename}
        </label>
        <div class="control">
          ${control}
        </div>
      </div>
      <div class="column is-narrow">
        ${button}
      </div>
    </div>
  </div>`
}

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
