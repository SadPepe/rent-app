<script setup lang="ts">
const route = useRoute()
const houseId = computed(() => route.params.id as string)

// Данные дома
const house = ref<any>(null)
const loading = ref(true)

// Форма бронирования
const checkIn = ref('')
const checkOut = ref('')
const guests = ref(1)
const totalPrice = ref(0)
const isAvailable = ref(false)
const message = ref('')
const checking = ref(false)
const booking = ref(false)

// Карусель
const carouselIndex = ref(0)

// Сегодня
const today = new Date().toISOString().split('T')[0]

// Вычисляемые
const maxGuests = computed(() => house.value?.max_guests || 1)
const pricePerNight = computed(() => house.value?.price_per_day || 0)

const minCheckout = computed(() => {
  if (!checkIn.value) return ''
  const d = new Date(checkIn.value)
  d.setDate(d.getDate() + 1)
  return d.toISOString().split('T')[0]
})

const isValidRange = computed(() => {
  return checkIn.value && checkOut.value && new Date(checkOut.value) > new Date(checkIn.value)
})

const alertClass = computed(() => {
  return isAvailable.value ? 'alert alert-success' : 'alert alert-error'
})

// Загрузка дома
onMounted(async () => {
  try {
    house.value = await $fetch(`http://localhost:8000/api/house-show/${houseId.value}`)
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

// Подсчёт цены
watch([checkIn, checkOut], () => {
  if (isValidRange.value) {
    const nights = Math.ceil(
      (new Date(checkOut.value).getTime() - new Date(checkIn.value).getTime()) /
      (1000 * 60 * 60 * 24)
    )
    totalPrice.value = nights * pricePerNight.value
  } else {
    totalPrice.value = 0
  }
  isAvailable.value = false
  message.value = ''
})

// Автозаполнение выезда
watch(checkIn, (val) => {
  if (val && !checkOut.value) {
    const d = new Date(val)
    d.setDate(d.getDate() + 1)
    checkOut.value = d.toISOString().split('T')[0]
  }
})

// Проверка доступности
async function checkAvailability() {
  if (!isValidRange.value) return
  checking.value = true
  try {
    const res = await $fetch(`/api/house-show/${houseId.value}/book-check`, {
      method: 'POST',
      body: { start: checkIn.value, end: checkOut.value, guests: guests.value }
    })
    if (res.available) {
      isAvailable.value = true
      message.value = `Доступно! ${res.total_price} ₽ за ${res.nights} ноч.`
    } else {
      isAvailable.value = false
      message.value = res.message || 'Даты заняты'
    }
  } catch {
    message.value = 'Ошибка проверки'
    isAvailable.value = false
  } finally {
    checking.value = false
  }
}

// Бронирование
async function confirmBooking() {
  booking.value = true
  try {
    const res = await $fetch(`/api/house-show/${houseId.value}/book-create`, {
      method: 'POST',
      body: { start: checkIn.value, end: checkOut.value, guests: guests.value }
    })
    if (res.success) {
      message.value = `Забронировано! ID: ${res.rental_id}`
      isAvailable.value = true
      // Сброс формы
      checkIn.value = ''
      checkOut.value = ''
      guests.value = 1
    }
  } catch {
    message.value = 'Ошибка бронирования'
    isAvailable.value = false
  } finally {
    booking.value = false
  }
}
</script>

<template>
  <div v-if="loading" class="flex justify-center py-20">
    <span class="loading loading-spinner loading-lg"></span>
  </div>

  <div v-else class="max-w-7xl mx-auto p-4">
    <div class="grid lg:grid-cols-3 gap-6">

      <!-- ЛЕВАЯ ЧАСТЬ: КАРУСЕЛЬ + ИНФО -->
      <div class="lg:col-span-2 space-y-6">

        <!-- КАРУСЕЛЬ -->
        <div class="carousel w-full rounded-xl overflow-hidden shadow-lg">
          <div
            v-for="(photo, i) in house.photos"
            :key="i"
            :id="`slide${i}`"
            class="carousel-item relative w-full"
          >
            <NuxtImg
              :src="photo"
              :alt="house.name"
              class="w-full h-96 object-cover"
              loading="lazy"
            />
            <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
              <a :href="`#slide${(i - 1 + house.photos.length) % house.photos.length}`" class="btn btn-circle">Prev</a>
              <a :href="`#slide${(i + 1) % house.photos.length}`" class="btn btn-circle">Next</a>
            </div>
          </div>
        </div>

        <!-- ИНФОРМАЦИЯ О ДОМЕ -->
        <div class="bg-base-100 text-base-content p-6 rounded-xl shadow">
          <h1 class="text-2xl font-bold mb-2">{{ house.name }}</h1>
          <p class="text-base-content/70 flex items-center gap-1">
            <Icon name="i-heroicons-map-pin" class="w-4 h-4" />
            {{ house.address }}
          </p>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-center">
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

          <div class="mt-6 prose prose-sm max-w-none">
            <p>{{ house.description }}</p>
          </div>
        </div>
      </div>

      <!-- ПРАВАЯ ЧАСТЬ: ФОРМА БРОНИРОВАНИЯ -->
      <div class="lg:col-span-1">
        <div class="bg-base-100 text-base-content rounded-xl shadow-md p-5 sticky top-6">
          <h2 class="text-xl font-bold text-primary mb-4">Бронирование</h2>

          <form @submit.prevent="checkAvailability" class="space-y-4">
            <!-- ЗАЕЗД -->
            <div>
              <label class="label text-xs font-medium">Заезд</label>
              <input
                v-model="checkIn"
                type="date"
                class="input input-bordered w-full text-sm"
                :min="today"
                required
              />
            </div>

            <!-- ВЫЕЗД -->
            <div>
              <label class="label text-xs font-medium">Выезд</label>
              <input
                v-model="checkOut"
                type="date"
                class="input input-bordered w-full text-sm"
                :min="minCheckout"
                :disabled="!checkIn"
                required
              />
            </div>

            <!-- ГОСТИ -->
            <div>
              <label class="label text-xs font-medium">Гостей</label>
              <select v-model="guests" class="select select-bordered w-full text-sm" required>
                <option v-for="n in maxGuests" :key="n" :value="n">
                  {{ n }} {{ n === 1 ? 'гость' : n <= 4 ? 'гостя' : 'гостей' }}
                </option>
              </select>
            </div>

            <!-- ЦЕНА -->
            <div v-if="totalPrice > 0" class="text-right">
              <p class="text-sm text-base-content/70">Итого:</p>
              <p class="text-2xl font-bold text-primary">{{ totalPrice }} ₽</p>
            </div>

            <!-- КНОПКИ -->
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

            <!-- ALERT -->
            <div v-if="message" role="alert" :class="alertClass" class="mt-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      :d="isAvailable ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'" />
              </svg>
              <span class="text-sm">{{ message }}</span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>