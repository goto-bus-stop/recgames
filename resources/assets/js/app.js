import 'es6-promise/auto'
import 'whatwg-fetch'

import { select } from './util'

import body from './body'
import tabs from './tabs'
import uploadForm from './upload-form'

function enhance (selector, fn) {
  for (const el of select(selector)) {
    fn(el)
  }
}

enhance('body', body)
enhance('.tabs', tabs)
enhance('#upload-form', uploadForm)
