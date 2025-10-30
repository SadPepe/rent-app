<template>
  <article
    class="relative bg-base-100 text-base-content rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform h-full flex flex-col"
  >
    <!-- Фото -->
    <div class="relative h-64 overflow-hidden">
      <NuxtImg
        :src="mainPhoto"
        :alt="house.name"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        loading="lazy"
        placeholder="/img/placeholder.jpg"
      />
    </div>

    <!-- Контент -->
    <div class="p-5 flex-1 flex flex-col">
      <h3 class="text-xl font-bold text-primary mb-2 line-clamp-1">
        {{ house.name }}
      </h3>

      <p class="text-sm text-base-content/70 flex items-center gap-1 mb-3">
        <Icon name="i-heroicons-map-pin" class="w-4 h-4" />
        {{ house.address }}
      </p>

      <!-- Удобства (иконки) -->
      <div class="flex gap-3 mb-4 text-base-content/60">
        <icon-tooltip
          icon="i-heroicons-wifi"
          label="Wi-Fi"
          :active="hasAmenity('Wi-Fi')"
        />
        <icon-tooltip
          icon="i-heroicons-car"
          label="Парковка"
          :active="hasAmenity('Парковка')"
        />
        <icon-tooltip
          icon="i-heroicons-fire"
          label="Камин"
          :active="hasAmenity('Камин')"
        />
        <icon-tooltip
          icon="i-heroicons-sparkles"
          label="Бассейн"
          :active="hasAmenity('Бассейн')"
        />
      </div>

      <!-- Цена и инфо -->
      <div class="flex justify-between items-end mt-auto">
        <div>
          <p class="text-2xl font-bold text-primary">
            {{ house.price_per_day }} ₽
          </p>
          <p class="text-xs text-base-content/60">за ночь</p>
        </div>
        <div class="text-right text-sm">
          <p>{{ house.bedrooms }} спальни • {{ house.max_guests }} гостей</p>
        </div>
        <div>
          <NuxtLink :to="`/house-show/${house.id}`" class="btn btn-primary">
              подробнее
          </NuxtLink>
        </div>
      </div>
    </div>
  </article>
</template>

<script setup lang="ts">
import { computed } from "vue";

// ОБЯЗАТЕЛЬНО: Получаем props
const props = defineProps<{
  house: {
    id: number;
    name: string;
    address: string;
    price_per_day: number;
    bedrooms: number;
    max_guests: number;
    amenities: string[];
    photos: string[];
  };
}>();

const hasAmenity = (amenity: string) => {
  return props.house.amenities?.includes(amenity);
};

// Теперь props доступен
const mainPhoto = computed(() => {
  return props.house.photos?.[0] || "/placeholder.jpg";
});
</script>
