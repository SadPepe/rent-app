<script setup lang="ts">
import emblaCarouselVue from 'embla-carousel-vue'
import Autoplay from 'embla-carousel-autoplay'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'


// === Роутинг ===
const route = useRoute()
const houseId = computed(() => route.params.id as string)

const {isAuthenticated, user} = useSanctumAuth();
const config = useRuntimeConfig()

// === Состояние ===
const house = ref<any>(null)
const loading = ref(true)
const bookedRanges = ref<{ start: string; end: string }[]>([])

// === Форма ===
const dateRange = ref<[Date, Date] | null>(null)
const guests = ref(1)
const totalPrice = ref(0)
const isAvailable = ref(false)
const message = ref('')
const checking = ref(false)
const booking = ref(false)

// === Карусель ===
const [emblaRef, emblaApi] = emblaCarouselVue({ loop: true }, [Autoplay({ delay: 4000 })])

const scrollNext = () => emblaApi.value?.scrollNext()
const scrollPrev = () => emblaApi.value?.scrollPrev()

// === Вычисляемые ===
const maxGuests = computed(() => house.value?.max_guests ?? 1)
const pricePerNight = computed(() => house.value?.price_per_day ?? 0)

const checkIn = computed(() => dateRange.value?.[0]?.toISOString().split('T')[0] ?? '')
const checkOut = computed(() => dateRange.value?.[1]?.toISOString().split('T')[0] ?? '')
const isValidRange = computed(() => !!dateRange.value?.[0] && !!dateRange.value?.[1])

const alertClass = computed(() =>
  isAvailable.value ? 'alert alert-success' : 'alert alert-error'
)

// === Отключённые даты (по документации) ===
const disabledDates = computed((): Date[] => {
  const dates: Date[] = []

  if (!bookedRanges.value?.length) return dates

  bookedRanges.value.forEach(({ start, end }) => {
    const startDate = new Date(start)
    const endDate = new Date(end)

    if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) return

    let current = new Date(startDate)
    while (current <= endDate) {
      dates.push(new Date(current))
      current.setDate(current.getDate() + 1)
    }
  })

  return dates
})

// === Загрузка данных (Promise.all + преобразование) ===
onMounted(async () => {

  await $fetch(`${config.public.apiBase}/sanctum/csrf-cookie`, {
    credentials: 'include'
  })
  try {
    const [houseData, rawBooked] = await Promise.all([
      $fetch(`${config.public.apiBase}/api/house-show/${houseId.value}`),
      $fetch(`${config.public.apiBase}/api/house-show/${houseId.value}/booked-dates`)
    ])

    house.value = houseData

    bookedRanges.value = rawBooked.map((item: any) => ({
      start: item.start_date.split(' ')[0],
      end: item.end_date.split(' ')[0]
    }))

    console.log('Забронированные даты:', bookedRanges.value)
  } catch (e) {
    console.error('Ошибка загрузки данных:', e)
    message.value = 'Не удалось загрузить дом'
  } finally {
    loading.value = false
  }
})

// === Подсчёт цены ===
watch(dateRange, () => {
  if (isValidRange.value) {
    const nights = Math.ceil(
      (dateRange.value![1].getTime() - dateRange.value![0].getTime()) /
        (1000 * 60 * 60 * 24)
    )
    totalPrice.value = nights * pricePerNight.value
  } else {
    totalPrice.value = 0
  }
  isAvailable.value = false
  message.value = ''
})

// === Проверка доступности ===
async function checkAvailability() {
  if (!isValidRange.value) return
  checking.value = true
  message.value = ''

  try {
    const res = await $fetch(`${config.public.apiBase}/api/house-show/${houseId.value}/book-check`, {
      method: 'POST',
      body: { start: checkIn.value, end: checkOut.value, guests: guests.value }
    })

    isAvailable.value = res.available
    totalPrice.value = res.total_price ?? 0
    message.value = res.available
      ? `Доступно! ${res.total_price} ₽`
      : res.message || 'Даты заняты'
  } catch (error: any) {
    if (error.response?._data) {
      const data = error.response._data
      isAvailable.value = data.available ?? false
      message.value = data.message || 'Ошибка валидации'
    } else {
      message.value = 'Нет связи с сервером'
      isAvailable.value = false
    }
  } finally {
    checking.value = false
  }
}

// === Бронирование ===
async function confirmBooking() {
  if (!isAuthenticated.value) {
    message.value = 'Авторизуйтесь для бронирования'
    return
  }

  if (!isValidRange.value) return
  booking.value = true
  message.value = ''

  try {
    // КРИТИЧНО: Получаем CSRF-cookie ПЕРЕД POST
    const client = useSanctumClient();
    const res = await client(`/api/house-show/${houseId.value}/book-create`, {
      method: 'POST',
      body: {
        start: checkIn.value,
        end: checkOut.value,
        guests: guests.value
      }
    })

    message.value = "Забронировано! Ожидайте звонка для подтверждения брони"
    isAvailable.value = true
    dateRange.value = null
    guests.value = 1
    totalPrice.value = 0

  } catch (error: any) {
    console.error('Booking error:', error)
    if (error.status === 401) {
      message.value = 'Требуется авторизация'
      navigateTo('/login')
    } else if (error.status === 419) {
      message.value = 'Ошибка CSRF. Попробуйте снова.'
    } else {
      message.value = error.response?._data?.message || 'Ошибка бронирования'
    }
  } finally {
    booking.value = false
  }
}
</script>

<template>
  <!-- Загрузка -->
  <div v-if="loading" class="flex justify-center py-20 min-h-screen">
    <span class="loading loading-spinner loading-lg"></span>
  </div>

  <!-- Основной контент -->
  <div v-else class="max-w-7xl mx-auto p-4">
    <div class="grid lg:grid-cols-3 gap-6">

      <!-- Левая часть: карусель + инфо -->
      <div class="lg:col-span-2 space-y-6">

        <!-- Карусель -->
        <div class="embla overflow-hidden rounded-xl">
          <div class="embla__viewport" ref="emblaRef">
            <div class="embla__container flex">
              <div
                v-for="(photo, i) in house.photos"
                :key="i"
                class="embla__slide flex-[0_0_100%] min-w-0"
              >
                <NuxtImg
                  :src="photo"
                  :alt="house.name"
                  class="w-full h-96 object-cover"
                  loading="lazy"
                />
              </div>
            </div>
          </div>
          <button @click="scrollPrev" class="embla__prev btn btn-circle btn-sm absolute left-4 top-1/2 -translate-y-1/2 z-10">
            Prev
          </button>
          <button @click="scrollNext" class="embla__next btn btn-circle btn-sm absolute right-4 top-1/2 -translate-y-1/2 z-10">
            Next
          </button>
        </div>

        <!-- Информация -->
        <div class="bg-base-100 p-6 rounded-xl shadow">
          <h1 class="text-2xl font-bold mb-2 text-primary">{{ house.name }}</h1>
          <p class="text-base-content/70 flex items-center gap-1">
            <Icon name="i-heroicons-map-pin" class="w-4 h-4" />
            {{ house.address }}
          </p>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-center text-base-content">
            <div>
              <p class="text-2xl font-bold text-primary">{{ house.price_per_day }} ₽</p>
              <p class="text-xs text-base-content/60">за ночь</p>
            </div>
            <div>
              <p class="text-2xl font-bold">{{ house.bedrooms }}</p>
              <p class="text-xs text-base-content/60">спальни</p>
            </div>
            <div>
              <p class="text-2xl font-bold">{{ house.bathrooms }}</p>
              <p class="text-xs text-base-content/60">ванные</p>
            </div>
            <div>
              <p class="text-2xl font-bold">{{ house.max_guests }}</p>
              <p class="text-xs text-base-content/60">гостей</p>
            </div>
          </div>

          <div class="mt-6 prose prose-sm max-w-none text-base-content">
            <p>{{ house.description }}</p>
          </div>
        </div>
      </div>

      <!-- Правая часть: форма -->
      <div class="lg:col-span-1 text-base-content">
        <div class="bg-base-100 rounded-xl shadow-md p-5 sticky top-6">
          <h2 class="text-xl font-bold text-primary mb-4">Бронирование</h2>

          <form @submit.prevent="checkAvailability" class="space-y-4">

            <!-- Календарь -->
            <client-only>
              <div>
                <label class="label text-xs font-medium">Заезд / Выезд</label>
                <VueDatePicker
                  v-model="dateRange"
                  range
                  :disabled-dates="disabledDates"
                  :min-date="new Date()"
                  :enable-time-picker="false"
                  format="dd.MM.yyyy"
                  locale="ru"
                  auto-apply
                  :month-change-on-scroll="false"
                  class="w-full"
                  placeholder="Выберите даты"
                  clearable
                >
                </VueDatePicker>
              </div>
            </client-only>

            <!-- Гости -->
            <div>
              <label class="label text-xs font-medium">Гостей</label>
              <select v-model="guests" class="select select-bordered w-full text-sm" required>
                <option v-for="n in maxGuests" :key="n" :value="n">
                  {{ n }} {{ n === 1 ? 'гость' : n <= 4 ? 'гостя' : 'гостей' }}
                </option>
              </select>
            </div>

            <!-- Цена -->
            <div v-if="totalPrice > 0" class="text-right">
              <p class="text-sm text-base-content/70">Итого:</p>
              <p class="text-2xl font-bold text-primary">{{ totalPrice }} ₽</p>
            </div>

            <!-- Кнопки -->
            <div class="flex gap-2">
              <button
                type="submit"
                class="btn btn-primary flex-1"
                :disabled="checking || !isValidRange"
              >
                {{ checking ? 'Проверка...' : 'Проверить' }}
              </button>
              <button
                v-if="isAvailable"
                @click="confirmBooking"
                type="button"
                class="btn btn-success flex-1"
                :disabled="booking"
              >
                {{ booking ? 'Бронь...' : 'Забронировать' }}
              </button>
            </div>
            
            <!-- Сообщение -->
            <div v-if="message" role="alert" :class="alertClass" class="mt-3">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 shrink-0 stroke-current"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  :d="isAvailable
                    ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                    : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'"
                />
              </svg>
              <span class="text-sm">{{ message }}</span>
            </div>
           
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.embla__prev,
.embla__next {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(4px);
}
.embla__prev:hover,
.embla__next:hover {
  background: white;
}
</style>