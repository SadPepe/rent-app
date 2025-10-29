<template>
  <div class="card bg-base-100 shadow-xl p-6 sticky top-6">
    <div class="text-2xl font-bold mb-4">
      {{ house.price_per_day }} ₽ <span class="text-sm font-normal text-base-content/70">/ ночь</span>
    </div>

    <form @submit.prevent="checkAvailability" class="space-y-4">
      <UPopover>
        <UButton
          variant="outline"
          size="lg"
          icon="i-heroicons-calendar"
          class="w-full justify-between"
        >
          <span v-if="dates.start && dates.end">
            {{ formatDate(dates.start) }} — {{ formatDate(dates.end) }}
          </span>
          <span v-else>Выберите даты</span>
        </UButton>
        <template #content>
          <UCalendar
            v-model="dates"
            :number-of-months="1"
            range
            :is-date-unavailable="isUnavailable"
            class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg"
          />
        </template>
      </UPopover>

      <UInput
        v-model.number="guests"
        type="number"
        :min="1"
        :max="house.max_guests"
        placeholder="Количество гостей"
        icon="i-heroicons-user-group"
      />

      <UButton
        type="submit"
        color="primary"
        size="lg"
        block
        :loading="checking"
        :disabled="!dates.start || !dates.end"
      >
        Проверить доступность
      </UButton>
    </form>

    <!-- Результат проверки -->
    <div v-if="availability !== null" class="mt-6 p-4 rounded-lg" :class="availability ? 'bg-success/10' : 'bg-error/10'">
      <p class="font-semibold" :class="availability ? 'text-success' : 'text-error'">
        {{ availability ? 'Доступно!' : 'Занято' }}
      </p>
      <p v-if="availability" class="mt-1">Итого: <strong>{{ totalPrice }} ₽</strong></p>
      <UButton
        v-if="availability"
        @click="createBooking"
        color="success"
        block
        class="mt-3"
        :loading="booking"
      >
        Забронировать
      </UButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { format, differenceInDays } from 'date-fns'

const props = defineProps<{
  house: any
}>()

const emit = defineEmits<{
  booked: []
}>()

const dates = ref<{ start: Date | null; end: Date | null }>({ start: null, end: null })
const guests = ref(1)
const checking = ref(false)
const booking = ref(false)
const availability = ref<boolean | null>(null)
const totalPrice = ref(0)
const bookedDates = ref<{ start: string; end: string }[]>([])

const config = useRuntimeConfig()
const client = useSanctumClient()

// Загрузка забронированных дат
onMounted(async () => {
  try {
    const data = await $fetch(`${config.public.apiBaseUrl}/api/house-show/${props.house.id}/booked-dates`)
    bookedDates.value = data
  } catch (e) {
    console.error(e)
  }
})

const isUnavailable = (date: Date) => {
  return bookedDates.value.some(range => {
    const start = new Date(range.start)
    const end = new Date(range.end)
    return date >= start && date <= end
  })
}

const nights = computed(() => {
  if (!dates.value.start || !dates.value.end) return 0
  return differenceInDays(dates.value.end, dates.value.start)
})

async function checkAvailability() {
  if (!dates.value.start || !dates.value.end) return

  checking.value = true
  try {
    const res = await $fetch(`${config.public.apiBaseUrl}/api/house-show/${props.house.id}/book-check`, {
      method: 'POST',
      body: {
        start: dates.value.start.toISOString().split('T')[0],
        end: dates.value.end.toISOString().split('T')[0],
        guests: guests.value
      }
    })

    availability.value = res.available
    totalPrice.value = res.total_price
  } catch (e) {
    availability.value = false
  } finally {
    checking.value = false
  }
}

async function createBooking() {
  booking.value = true
  try {
    await client(`${config.public.apiBaseUrl}/api/house-show/${props.house.id}/book-create`, {
      method: 'POST',
      body: {
        start: dates.value.start?.toISOString().split('T')[0],
        end: dates.value.end?.toISOString().split('T')[0],
        guests: guests.value
      }
    })

    useToast().add({ title: 'Успешно!', description: 'Бронь создана', color: 'success' })
    emit('booked')
    availability.value = null
    dates.value = { start: null, end: null }
  } catch (e) {
    useToast().add({ title: 'Ошибка', description: 'Не удалось создать бронь', color: 'error' })
  } finally {
    booking.value = false
  }
}

function formatDate(date: Date | null) {
  if (!date) return ''
  return format(date, 'd MMM')
}
</script>