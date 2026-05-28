import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import SearchBar from '~/components/search/SearchBar.vue'

describe('SearchBar', () => {

  it('sendet search-Event mit dem eingegebenen Ort', async () => {
    const wrapper = mount(SearchBar)

    // Wert ins Input-Feld schreiben (simuliert Tastatureingabe)
    await wrapper.find('input').setValue('München')

    // Formular absenden (simuliert Klick auf Submit-Button)
    await wrapper.find('form').trigger('submit')

    // wrapper.emitted() gibt alle ausgelösten Events zurück
    // Format: { 'search': [[{ city: 'München' }]] }
    expect(wrapper.emitted('search')).toBeTruthy()
    expect(wrapper.emitted('search')![0]).toEqual([{ city: 'München' }])
  })

  it('sendet kein Event bei leerem Feld', async () => {
    const wrapper = mount(SearchBar)

    // Formular ohne Eingabe abschicken
    await wrapper.find('form').trigger('submit')

    // emitted('search') ist undefined wenn kein Event ausgelöst wurde
    expect(wrapper.emitted('search')).toBeFalsy()
  })

  it('trimmt Leerzeichen aus der Eingabe', async () => {
    const wrapper = mount(SearchBar)
    await wrapper.find('input').setValue('  München  ')
    await wrapper.find('form').trigger('submit')

    expect(wrapper.emitted('search')![0]).toEqual([{ city: 'München' }])
  })
})
