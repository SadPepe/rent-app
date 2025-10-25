<template>
     <div v-if="house" class="max-w-4xl mx-auto mt-8">
      <div
        class="card bg-base-100 shadow-lg hover:shadow-2xl transition-shadow duration-300 ease-in-out rounded-lg overflow-hidden"
      >
        <figure class="relative h-56">
          <!-- Фотография -->
          <NuxtImg
            v-if="house.photos && house.photos.length"
            provider="backend"
            :src="house.photos[0]"
            :alt="`Фото ${house.name}`"
            class="w-full h-full object-cover"
          />
          <!-- Заглушка, если фото нет -->
          <div v-else class="w-full h-full bg-base-200 flex items-center justify-center">
            <span class="text-base-content opacity-50">Нет фото</span>
          </div>
          <!-- Цена поверх фото -->
          <div class="absolute bottom-0 right-0 bg-primary/80 backdrop-blur-sm text-primary-content py-1 px-3 rounded-tl-lg">
            <span class="text-lg font-bold">₽{{ house.price_per_day }}</span>
            <span class="text-sm">/ночь</span>
          </div>
        </figure>

        <!-- Информация о доме -->
        <div class="card-body p-4 flex flex-col">
          <h2 class="card-title text-lg font-bold truncate" :title="house.name">{{ house.name }}</h2>
          <p class="text-sm opacity-70 -mt-1 mb-2 truncate" :title="house.address">{{ house.address }}</p>

          <!-- Характеристики (спальни, ванные, гости) -->
          <div class="flex items-center gap-4 text-sm opacity-90 my-2">
            <div class="flex items-center gap-1.5" title="Спальни">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
              <span>{{ house.bedrooms }}</span>
            </div>
            <div class="flex items-center gap-1.5" title="Ванные комнаты">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
              <span>{{ house.bathrooms }}</span>
            </div>
            <div class="flex items-center gap-1.5" title="Максимум гостей">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.28-1.25-.7-1.686M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.28-1.25.7-1.686m0 0A12.145 12.145 0 0112 13c2.814 0 5.337 1.01 7.3 2.686M12 13a3 3 0 100-6 3 3 0 000 6z"></path></svg>
              <span>{{ house.max_guests }}</span>
            </div>
          </div>

          <!-- Удобства в виде бейджей -->
          <div v-if="house.amenities && house.amenities.length" class="flex flex-wrap gap-2 my-3">
            <div v-for="amenity in house.amenities.slice(0, 4)" :key="amenity" class="badge badge-outline badge-sm">
              {{ amenity }}
            </div>
            <div v-if="house.amenities.length > 4" class="badge badge-outline badge-sm">
              +{{ house.amenities.length - 4 }}
            </div>
          </div>
          
          <!-- Краткое описание -->
          <p class="text-sm opacity-80 flex-grow min-h-[40px]">{{ house.description.substring(0, 70) }}{{ house.description.length > 70 ? '...' : '' }}</p>

          <!-- Кнопка -->
          <div class="card-actions justify-end mt-4">

            <NuxtLink :to="`/house-show/${house.id}`" class="btn btn-primary w-full">
              Подробнее
            </NuxtLink>
          </div>
        </div>
      </div>
     </div>
</template>
<script lang="ts" setup>
import {ref, onMounted} from 'vue';

const route = useRoute();

const houseid = route.params.id;

interface House {
  id: number;
  name: string;
  description: string;
  address: string;
  price_per_day: number;
  bedrooms: number;
  bathrooms: number;
  max_guests: number;
  amenities: string[];
  is_active: boolean;
  created_at: string;
  updated_at: string;
  photos: string[] | null;
}

const house = ref<House | null>(null);

onMounted(async () => {
  try {
    const response = await fetch(`http://localhost:8000/api/house-show/${houseid}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const responseData = await response.json();

    // Если сервер возвращает { data: { ... } }
    if (responseData.data) {
      house.value = responseData.data;
    } else {
      // Если сервер возвращает просто объект дома
      house.value = responseData;
    }
  } catch (error) {
    console.error("Failed to fetch house:", error);
  }
});
</script>
