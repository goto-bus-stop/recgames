import { h } from 'preact'

export default function UploadStatus ({
  filename,
  complete = 0,
  error = null,
  url = null
}) {
  const button = url ? (
    <a class='button is-primary' href={url} target='_blank'>View</a>
  ) : (
    <a class='button is-primary is-loading' href='#'>View</a>
  )

  let control
  if (url) {
    control = <span class='is-success-text'>Upload complete.</span>
  } else if (error) {
    control = <span class='is-danger-text'>{error}</span>
  } else {
    control = <progress class='progress' value={complete} max='100' />
  }

  return (
    <div class='box'>
      <div class='columns'>
        <div class='column'>
          <label class='label'>
            {filename}
          </label>
          <div class='control'>
            {control}
          </div>
        </div>
        <div class='column is-narrow'>
          {button}
        </div>
      </div>
    </div>
  )
}
