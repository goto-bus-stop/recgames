import yo from 'yo-yo'

export default function uploadStatus ({
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
