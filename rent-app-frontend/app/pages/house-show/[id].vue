<template>
  <div class="max-w-7xl mx-auto mt-8 grid grid-cols-3 grid-rows-3 gap-2.5">
    <div class="embla col-start-1 col-end-4">
      <div class="embla__viewport" ref="emblaRef">
        <div class="embla__container">
          <div v-for="slide in slides" :key="slide.id" class="embla__slide">
            <NuxtImg :src="slide.url" class="aspect-video w-full rounded-2xl" />
          </div>
        </div>
      </div>

      <button class="embla__prev" @click="scrollPrev">Prev</button>
      <button class="embla__next" @click="scrollNext">Next</button>
    </div>

    <div class="col-span-2 row-span-2 gap-2 flex flex-col">
      <div class="border-accent border-2 rounded-2xl p-4">
        <h1 class="text-2xl font-bold text-primary">{{ house?.name }}</h1>
        <p class="text-accent">{{ house?.address }}</p>
        <p class="text-accent">Цена за день: {{ house?.price_per_day }}</p>
        <p class="text-accent">Удобства: {{ house?.amenities.join(", ") }}</p>
      </div>
      <div class="border-accent border-2 rounded-2xl p-4">
        <h1 class="text-2xl font-bold text-primary">Описание</h1>
        <p class="text-accent">{{ house?.description }}</p>
      </div>
    </div>
    <div class="border-accent border-2 col-span-1 row-span-2 rounded-2xl p-4">
      <h1 class="text-2xl font-bold text-primary">Форма</h1>
      <form class="flex flex-col gap-4 text-base-content">
        <UPopover>
          <UButton class="bg-primary text-primary-content hover:bg-primary-content hover:text-primary"  variant="subtle" icon="i-lucide-calendar">
            <template v-if="modelValue.start" >
              <template v-if="modelValue.end">
                {{ df.format(modelValue.start.toDate(getLocalTimeZone())) }} -
                {{ df.format(modelValue.end.toDate(getLocalTimeZone())) }}
              </template>

              <template v-else >
                {{ df.format(modelValue.start.toDate(getLocalTimeZone())) }}
              </template>
            </template>
            <template v-else> Pick a date </template>
          </UButton>

          <template #content>
            <UCalendar
              v-model="modelValue"
              class="p-2"
              :number-of-months="1"
              :is-date-unavailable="isDateUnavailable"
              range
            />
          </template>
        </UPopover>
        <label for="guests">Количество гостей</label>
        <input type="number" id="guests" name="guests" min="1" max="10" />
        <button type="submit" class="btn btn-primary">Подобрать</button>
      </form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import emblaCarouselVue from "embla-carousel-vue";
import Autoplay from "embla-carousel-autoplay";
import { CalendarDate, DateFormatter, parseDate, getLocalTimeZone } from '@internationalized/date'
import type { DateValue } from '@internationalized/date'

const df = new DateFormatter('ru-RU', {
  dateStyle: 'long'
})

const modelValue = shallowRef({
  start: new CalendarDate(2025, 10, 1),
  end: new CalendarDate(2025, 10, 9)
})

const bookedDates = shallowRef<CalendarDate[]>([]);
// Флаг, который покажет, что даты были загружены
const datesLoaded = ref(false);

const isDateUnavailable = (date: DateValue) => {
  // Если даты еще не загружены, ничего не блокируем
  if (!datesLoaded.value) {
    return false;
  }
  const isBooked = bookedDates.value.some(bookedDate => date.compare(bookedDate) === 0);
  // Логируем только если нашли заблокированную дату, чтобы не засорять консоль

  return isBooked;
}

const route = useRoute();
const houseid = route.params.id;
const house = ref<House | null>(null);


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

onMounted(async () => {
  try {
    // Загружаем информацию о доме
    const responseData = await fetch(
      `http://localhost:8000/api/house-show/${houseid}`
    ).then((res) => res.json());
    house.value = responseData;

    // Загружаем забронированные даты
    const bookedDatesData = await fetch(
      `http://localhost:8000/api/house-show/${houseid}/booked-dates`
    ).then((res) => res.json());

    const dates: CalendarDate[] = [];
    if (Array.isArray(bookedDatesData)) {
      for (const range of bookedDatesData) {
        // Убедимся, что в range есть нужные свойства
        if (range && range.start_date && range.end_date) {
          let currentDate = parseDate(range.start_date.split(' ')[0]);
          const endDate = parseDate(range.end_date.split(' ')[0]);

          while (currentDate.compare(endDate) <= 0) {
            dates.push(currentDate);
            currentDate = currentDate.add({ days: 1 });
          }
        }
      }
    }
    
    bookedDates.value = dates;
  } catch (error) {
    console.error("Ошибка при загрузке данных:", error);
  }
});


const [emblaRef, emblaApi] = emblaCarouselVue({ loop: true }, [Autoplay()]);

const slides = computed(() => {
  if (house.value && house.value.photos) {
    return house.value.photos.map((url, id) => ({ id, url }));
  }
  return [];
});

function scrollNext() {
  emblaApi.value?.scrollNext();
}

function scrollPrev() {
  emblaApi.value?.scrollPrev();
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
</style>
