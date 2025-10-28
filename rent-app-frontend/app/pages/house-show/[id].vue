<template>
  <div v-if="pending" class="max-w-7xl mx-auto mt-8 text-center text-gray-600">Загрузка данных...</div>
  <div v-else-if="error" class="max-w-7xl mx-auto mt-8 text-center text-red-500">Ошибка загрузки: {{ error.message }}</div>
  <div v-else class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <!-- Карусель -->
    <div class="embla relative rounded-2xl overflow-hidden shadow-lg">
      <div class="embla__viewport" ref="emblaRef">
        <div class="embla__container">
          <div v-for="slide in slides" :key="slide.id" class="embla__slide">
            <NuxtImg :src="slide.url" class="aspect-video w-full object-cover" placeholder="/placeholder.jpg" />
          </div>
        </div>
      </div>
      <button class="embla__prev absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 p-2 rounded-full shadow-md hover:bg-white" @click="scrollPrev">
        <Icon name="i-heroicons-chevron-left" class="w-6 h-6 text-gray-800" />
      </button>
      <button class="embla__next absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 p-2 rounded-full shadow-md hover:bg-white" @click="scrollNext">
        <Icon name="i-heroicons-chevron-right" class="w-6 h-6 text-gray-800" />
      </button>
      <!-- Dots индикаторы -->
      <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        <button v-for="(slide, index) in slides" :key="index" class="w-3 h-3 rounded-full" :class="{ 'bg-primary': index === selectedSlide, 'bg-gray-300': index !== selectedSlide }" @click="scrollTo(index)"></button>
      </div>
    </div>

    <!-- Основной контент: Детали + Описание + Форма -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
      <div class="md:col-span-2 flex flex-col gap-6">
        <!-- Детали дома -->
        <div class="bg-base-200 rounded-2xl shadow-md p-6 transition-shadow hover:shadow-lg">
          <h1 class="text-3xl font-bold text-primary mb-2">{{ house?.name }}</h1>
          <p class="text-base-content flex items-center mb-4">
            <Icon name="i-heroicons-map-pin" class="w-5 h-5 mr-2 text-base-content" />
            {{ house?.address }}
          </p>
          <p class="text-xl font-semibold text-base-content mb-4">Цена за день: {{ house?.price_per_day }} руб.</p>
          <div class="grid grid-cols-2 gap-4 mb-4">
            <p class="flex items-center text-base-content"><Icon name="i-heroicons-bed" class="w-5 h-5 mr-2 text-base-content" /> Спальни: {{ house?.bedrooms }}</p>
            <p class="flex items-center text-base-content"><Icon name="i-heroicons-bath" class="w-5 h-5 mr-2 text-base-content" /> Ванные: {{ house?.bathrooms }}</p>
            <p class="flex items-center text-base-content"><Icon name="i-heroicons-user-group" class="w-5 h-5 mr-2 text-base-content" /> Макс. гостей: {{ house?.max_guests }}</p>
          </div>
          <h2 class="text-xl font-semibold text-primary mb-2">Удобства</h2>
          <ul class="grid grid-cols-2 gap-2 text-gray-700">
            <li v-for="amenity in house?.amenities" :key="amenity" class="flex items-center">
              <Icon name="i-heroicons-check-circle" class="w-5 h-5 mr-2 text-green-500" />
              {{ amenity }}
            </li>
          </ul>
        </div>

        <!-- Описание -->
        <div class="bg-base-200 rounded-2xl shadow-md p-6 transition-shadow hover:shadow-lg">
          <h2 class="text-2xl font-bold text-primary mb-4">Описание</h2>
          <p class="text-base-content leading-relaxed">{{ house?.description }}</p>
        </div>
      </div>

      <!-- Форма бронирования (sticky на десктопе) -->
      <div class="md:col-span-1">
        <div class="bg-base-200 rounded-2xl shadow-md p-6 sticky top-8 transition-shadow hover:shadow-lg">
          <h2 class="text-2xl font-bold text-primary mb-4">Бронирование</h2>
          <form @submit.prevent="handleSubmit" class="flex flex-col gap-4">
            <UPopover>
              <UButton class="bg-primary text-primary-content hover:bg-primary-dark justify-between" variant="solid" icon="i-heroicons-calendar">
                <span v-if="modelValue.start">
                  <span v-if="modelValue.end">
                    {{ df.format(modelValue.start.toDate(getLocalTimeZone())) }} -
                    {{ df.format(modelValue.end.toDate(getLocalTimeZone())) }}
                  </span>
                  <span v-else>
                    {{ df.format(modelValue.start.toDate(getLocalTimeZone())) }}
                  </span>
                </span>
                <span v-else>Выберите даты</span>
              </UButton>
              <template #content>
                <UCalendar
                  v-model="modelValue"
                  class="p-2 bg-primary text-primary-content shadow-lg rounded-lg"
                  :number-of-months="1"
                  :is-date-unavailable="isDateUnavailable"
                  range
                />
              </template>
            </UPopover>
            <UInput v-model="guests" type="number" label="Количество гостей" :min="1" :max="house?.max_guests || 10" icon="i-heroicons-user-group" class="bg-primary text-primary-content" />
            <UButton type="submit" class="bg-accent text-white hover:bg-accent-dark" :disabled="!modelValue.start || !modelValue.end || guests < 1">Проверить доступность</UButton>
            <UButton v-if="showBookingButton" @click="confirmBooking" class="bg-green-500 text-white hover:bg-green-600">Бронировать</UButton>
          </form>
          <p v-if="bookingMessage" class="mt-4 text-sm" :class="{ 'text-green-600': bookingMessage.includes('Доступно'), 'text-red-600': !bookingMessage.includes('Доступно') }">{{ bookingMessage }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts" setup>
import { ref, computed, watchEffect, watch } from "vue";
import { useRoute } from "vue-router";
import emblaCarouselVue from "embla-carousel-vue";
import Autoplay from "embla-carousel-autoplay";
import { CalendarDate, DateFormatter, parseDate, getLocalTimeZone } from '@internationalized/date'
import type { DateValue } from '@internationalized/date'

const df = new DateFormatter('ru-RU', { dateStyle: 'long' });

const modelValue = ref<{ start?: CalendarDate; end?: CalendarDate }>({});
const guests = ref(1);
const bookingMessage = ref('');
const showBookingButton = ref(false);
const { isAuthenticated, user } = useSanctumAuth();  // Из модуля
const client = useSanctumClient();

const route = useRoute();
const houseId = route.params.id as string;

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
const bookedRanges = ref<{ start: CalendarDate; end: CalendarDate }[]>([]);
const pending = ref(true);
const error = ref<Error | null>(null);

const config = useRuntimeConfig();
const apiBaseUrl = config.public.apiBaseUrl as string; // Type assertion for safety

const { data: houseData, pending: housePending, error: houseError } = await useAsyncData<House>(
  `house-${houseId}`,
  () => $fetch(`${apiBaseUrl}/api/house-show/${houseId}`)
);

const { data: bookedDatesData, pending: datesPending, error: datesError } = await useAsyncData<unknown[]>(
  `booked-dates-${houseId}`,
  () => $fetch(`${apiBaseUrl}/api/house-show/${houseId}/booked-dates`)
);

watchEffect(() => {
  if (!housePending.value && !datesPending.value) {
    pending.value = false;
    if (houseError.value || datesError.value) {
      error.value = houseError.value || datesError.value;
      return;
    }
    house.value = houseData.value;

    const ranges: { start: CalendarDate; end: CalendarDate }[] = [];
    if (Array.isArray(bookedDatesData.value)) {
      for (const range of bookedDatesData.value) {
        if (range && (range as any).start_date && (range as any).end_date) {
          ranges.push({
            start: parseDate((range as any).start_date.split(' ')[0]),
            end: parseDate((range as any).end_date.split(' ')[0])
          });
        }
      }
    }
    bookedRanges.value = ranges;
  }
});

const isDateUnavailable = (date: DateValue) => {
  return bookedRanges.value.some(range => date.compare(range.start) >= 0 && date.compare(range.end) <= 0);
};

async function handleSubmit() {
  if (!modelValue.value.start || !modelValue.value.end || guests.value < 1) {
    bookingMessage.value = 'Заполните все поля правильно.';
    return;
  }
  if (!isAuthenticated.value) {
    bookingMessage.value = 'Пожалуйста, войдите в аккаунт.';
    return;
  }
  try {
    const response = await $fetch(`${apiBaseUrl}/api/house-show/${houseId}/book-check`, {
      method: 'POST',
      credentials: 'include',  // Для куки Sanctum
      body: {
        start: modelValue.value.start.toString(),
        end: modelValue.value.end.toString(),
        guests: guests.value
      }
    });
    if (response.available) {
      bookingMessage.value = `Доступно! Общая цена: ${response.total_price} руб.`;
      showBookingButton.value = true;  // Показать кнопку бронирования
    } else {
      bookingMessage.value = response.message;
    }
  } catch (err) {
    bookingMessage.value = 'Ошибка проверки доступности.';
    console.error('Submit error:', err);
  }
}

async function confirmBooking() {
  try {
    const response = await client(`${apiBaseUrl}/api/house-show/${houseId}/book-create`, {
      method: 'POST',
      credentials: 'include',
      body: {
        start: modelValue?.value.start?.toString(),
        end: modelValue?.value.end?.toString(),
        guests: guests.value
      }
    });
    if (response.available) {
      bookingMessage.value = response.message + ` ID: ${response.rental_id}`;
      showBookingButton.value = false;
      // Опционально: Redirect на профиль с бронированиями
    } else {
      bookingMessage.value = response.message;
    }
  } catch (err) {
    bookingMessage.value = 'Ошибка создания бронирования.';
    console.error(err);
  }
}

const [emblaRef, emblaApi] = emblaCarouselVue({ loop: true }, [Autoplay()]);
const selectedSlide = ref(0);

const slides = computed(() => {
  if (house.value && house.value.photos) {
    return house.value.photos.map((url, index) => ({ id: index, url }));
  }
  return [{ id: 0, url: '/placeholder.jpg' }];
});

function scrollNext() {
  emblaApi.value?.scrollNext();
}

function scrollPrev() {
  emblaApi.value?.scrollPrev();
}

watch(emblaApi, (api) => {
  if (api) {
    api.on('select', () => {
      selectedSlide.value = api.selectedScrollSnap();
    });
  }
});

function scrollTo(index: number) {
  emblaApi.value?.scrollTo(index);
}
</script>

<style scoped>
.embla {
  overflow: hidden;
}
.embla__container {
  display: flex;
}
.embla__slide {
  flex: 0 0 100%;
  min-width: 0;
}

/* Transitions */
button:hover { transition: all 0.3s ease; }
</style>