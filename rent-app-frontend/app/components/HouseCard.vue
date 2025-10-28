<template>
  <NuxtLink :to="`/house-show/${house.id}`" class="block group">
    <div class="card bg-base-100 shadow-lg hover:shadow-2xl transition-all duration-300 h-full overflow-hidden rounded-2xl">
      <figure class="relative h-56 overflow-hidden">
        <NuxtImg
          :src="mainPhoto"
          :alt="house.name"
          class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
          loading="lazy"
        />
        <div class="badge badge-primary absolute top-4 left-4">Новинка</div>
      </figure>
      <div class="card-body p-5">
        <h3 class="card-title text-lg line-clamp-1">{{ house.name }}</h3>
        <p class="text-sm text-base-content/70 flex items-center gap-1">
          <Icon name="i-heroicons-map-pin" class="w-4 h-4" />
          {{ house.address }}
        </p>
        <div class="flex justify-between items-end mt-3">
          <div>
            <p class="text-2xl font-bold text-primary">{{ house.price_per_day }} ₽</p>
            <p class="text-xs text-base-content/60">за ночь</p>
          </div>
          <div class="text-right text-sm">
            <p>{{ house.bedrooms }} сп. • {{ house.max_guests }} гостей</p>
          </div>
        </div>
      </div>
    </div>
  </NuxtLink>
</template>

<script setup lang="ts">
import { computed } from 'vue'

// ОБЯЗАТЕЛЬНО: Получаем props
const props = defineProps<{
  house: {
    id: number
    name: string
    address: string
    price_per_day: number
    bedrooms: number
    max_guests: number
    photos: string[]
  }
}>()

// Теперь props доступен
const mainPhoto = computed(() => {
  return props.house.photos?.[0] || '/placeholder.jpg'
})
</script>