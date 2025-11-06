<template>
  <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300">
    <!-- Опционально: фото дома -->
    <figure class="">
      <NuxtImg :src="house.photos[0]" alt="Дом" class="rounded-t-xl w-full h-48 object-cover" />
    </figure>

    <div class="card-body p-4">
      <!-- Название -->
      <h2 class="card-title text-xl font-bold text-primary">
        {{ house.name }}
      </h2>

      <!-- Описание -->
      <p class="text-base-content/70 text-sm line-clamp-3">
        {{ house.description }}
      </p>

      <!-- Удобства (из массива) -->
      <div class="mt-3">
        <h3 class="font-semibold text-sm mb-1 text-base-content">Удобства:</h3>
        <div class="flex flex-wrap gap-2">
          <div v-for="amenity in house.amenities" :key="amenity" class="badge badge-secondary text-secondary-content badge-sm">
            {{ amenity }}
          </div>
        </div>
      </div>

      <!-- Цены: будни / выходные -->
      <div class="flex justify-center mt-2 gap-4">
        <div class="flex w-full">
          <div class="card bg-base-200 rounded-box grid grow place-items-center">
          <p class="text-xs text-base-content/70">Будни</p>
          <p class="text-lg font-bold text-primary">
            {{ house.price_per_day }} ₽
          </p>
        </div>
          <div class="divider divider-horizontal"></div>
          <div class="card bg-base-200 rounded-box grid grow place-items-center">
          <p class="text-xs text-base-content/70">Будни</p>
          <p class="text-lg font-bold text-primary">
            {{ house.price_per_day }} ₽
          </p>
        </div>
        </div>
        <!-- Кнопка "Подробнее" -->
        <div class="card-actions justify-end mt-5">
          <NuxtLink :to="`/house-show/${house.id}`" class="btn btn-primary btn-sm w-full sm:btn-md sm:w-auto">
            Подробнее
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

// ОБЯЗАТЕЛЬНО: Получаем props
const props = defineProps<{
  house: {
    id: number;
    name: string;
    address: string;
    description: string;
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
