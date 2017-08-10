import { uploadProgress, uploadFinish } from '../actions'

const createUrl = window.recgames.api.recordedGames.create

function tryParse (json) {
  try {
    return JSON.parse(json)
  } catch (e) {}
}

const uploadGame = ({ dispatch }) => next => action => {
  if (action.type === 'UPLOAD_START') {
    const { file } = action.payload
    const { id } = action.meta
    const formData = new FormData()
    formData.append('recorded_game', file)

    fetch(createUrl, {
      method: 'post',
      headers: { accept: 'application/json' }
    }).then((response) => response.json()).then(({ links }) => {
      const xhr = new XMLHttpRequest()
      xhr.open('POST', links.upload)
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
      xhr.setRequestHeader('Accept', 'application/json')
      xhr.onload = () => {
        const result = tryParse(xhr.responseText)
        let finish
        if (xhr.status === 200) {
          finish = uploadFinish(id, { url: result.links.page })
        } else {
          finish = uploadFinish(id, result ? {
            error: result.errors.map((err) => err.title).join(' '),
            url: result.links ? result.links.page : null
          } : { error: 'Unknown error.' })
        }
        dispatch(finish)
      }
      xhr.upload.onprogress = (event) => {
        dispatch(uploadProgress(id, {
          complete: (event.loaded / event.total) * 100
        }))
      }

      xhr.send(formData)
    })
  }

  next(action)
}

export default uploadGame
