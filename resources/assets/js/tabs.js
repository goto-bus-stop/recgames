import { select } from './util'
import isDescendant from 'is-descendant'

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
  if (target) {
    select(target).forEach((panel) => {
      panel.classList.add('is-active')
    })

    // Update the hash, but don't scroll.
    const oldScroll = document.body.scrollTop
    window.location.hash = target
    document.body.scrollTop = oldScroll
  }
}

function setActiveTab(tablist, name) {
  const selectedTab = select(`[role="tab"][aria-controls="${CSS.escape(name)}"]`)[0]
  if (isDescendant(tablist, selectedTab)) {
    deselectTabs(tablist)
    selectTab(selectedTab)
  }
}

export default function apply (tablist) {
  if (window.location.hash) {
    const tabName = window.location.hash.slice(1)
    setActiveTab(tablist, tabName)
  }

  window.addEventListener('hashchange', () => {
    const tabName = window.location.hash.slice(1)
    setActiveTab(tablist, tabName)
  })

  tablist.addEventListener('click', (event) => {
    if (event.target.getAttribute('role') !== 'tab') {
      return
    }

    event.preventDefault()
    deselectTabs(tablist)
    selectTab(event.target)
  }, false)
}
