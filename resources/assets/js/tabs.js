import { select } from './util'

function deselectTabs (tablist) {
  select('[role="tab"]', tablist).forEach((tabHref) => {
    const tab = tabHref.parentNode
    const target = tabHref.getAttribute('href')
    if (tab.classList.contains('is-active')) {
      tab.classList.remove('is-active')
      target && select(target).forEach((panel) => {
        panel.classList.remove('is-active')
      })
    }
  })
}

function selectTab (tabHref) {
  const tab = tabHref.parentNode
  const target = tabHref.getAttribute('href')
  tab.classList.add('is-active')
  target && select(target).forEach((panel) => {
    panel.classList.add('is-active')
  })
}

export default function apply (tablist) {
  tablist.addEventListener('click', (event) => {
    deselectTabs(tablist)
    selectTab(event.target)
  }, false)
}
