export function select (selector, base = document) {
  return [].slice.call(base.querySelectorAll(selector))
}
