import { joinURL } from 'ufo'
import type { ProviderGetImage } from '@nuxt/image'

export const getImage: ProviderGetImage = (src, { modifiers, baseURL } = {}) => {
  // Этот провайдер не использует модификаторы (для обрезки, изменения размера и т.д.)
  // Он просто соединяет базовый URL и путь к изображению.
  const url = joinURL(baseURL || '', src)

  return {
    url
  }
}