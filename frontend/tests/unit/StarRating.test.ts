import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import StarRating from '~/components/ui/StarRating.vue'

// describe() gruppiert zusammengehörige Tests — hier alles zur StarRating-Komponente
describe('StarRating', () => {

  // it() beschreibt einen einzelnen Testfall im Klartext
  it('zeigt 3 ausgefüllte Sterne bei rating=3', () => {
    // mount() rendert die Vue-Komponente im Test (kein Browser nötig)
    const wrapper = mount(StarRating, {
      props: { rating: 3 },
    })

    // Alle ★-Zeichen zählen
    const stars = wrapper.findAll('span').filter(s => s.text() === '★')
    const filled = stars.filter(s => s.classes('text-yellow-400'))
    const empty  = stars.filter(s => s.classes('text-gray-300'))

    expect(filled).toHaveLength(3)
    expect(empty).toHaveLength(2)
  })

  it('zeigt den Durchschnittswert als Text', () => {
    const wrapper = mount(StarRating, { props: { rating: 4.3 } })
    expect(wrapper.text()).toContain('4.3')
  })

  it('zeigt 0 ausgefüllte Sterne bei rating=0', () => {
    const wrapper = mount(StarRating, { props: { rating: 0 } })
    const filled = wrapper.findAll('span').filter(s => s.classes('text-yellow-400'))
    expect(filled).toHaveLength(0)
  })
})
