<template>
    <div class="min-h-screen bg-base-200 py-8 mt-8">
      <div class="max-w-6xl mx-auto px-4">
        <!-- Заголовок и инфа пользователя -->
        <div class="bg-base-100 rounded-xl shadow-md p-6 mb-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center">
                <Icon name="i-heroicons-user" class="w-8 h-8 text-primary" />
              </div>
              <div>
                <h1 class="text-2xl font-bold text-primary">{{ user?.name }}</h1>
                <p class="text-base-content/70">{{ user?.email }}</p>
              </div>
            </div>
            <NuxtLink to="/edit-profile" class="btn btn-primary btn-sm">
              Редактировать профиль
            </NuxtLink>
          </div>
        </div>
  
        <!-- Секция бронирований -->
        <div class="bg-base-100 rounded-xl shadow-md p-6">
          <h2 class="text-xl font-bold mb-4 text-primary flex items-center gap-2">
            <Icon name="i-heroicons-calendar" class="w-5 h-5" />
            Мои бронирования
          </h2>
  
          <!-- Загрузка -->
          <div v-if="loading" class="flex justify-center py-8">
            <span class="loading loading-spinner loading-lg text-primary"></span>
          </div>
  
          <!-- Пусто -->
          <div v-else-if="!bookings?.length" class="text-center py-8">
            <Icon name="i-heroicons-calendar-days" class="w-16 h-16 text-base-content/50 mx-auto mb-4" />
            <p class="text-base-content/70">У вас пока нет бронирований</p>
            <NuxtLink to="/" class="btn btn-primary mt-4">Найти дом</NuxtLink>
          </div>
  
          <!-- Таблица бронирований -->
          <div v-else class="overflow-x-auto">
            <table class="table w-full">
              <thead>
                <tr>
                  <th>Дом</th>
                  <th>Даты</th>
                  <th>Гостей</th>
                  <th>Цена</th>
                  <th>Статус</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="booking in bookings" :key="booking.id" class="hover">
                  <td>
                    <div class="flex items-center gap-3">
                      <NuxtImg
                        v-if="booking.house?.photos[0]"
                        :src="booking.house.photos[0]"
                        alt="Дом"
                        class="w-12 h-12 rounded object-cover"
                      />
                      <div>
                        <p class="font-semibold">{{ booking.house?.name }}</p>
                        <p class="text-sm text-base-content/70">{{ booking.house?.address }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="font-medium">{{ formatDate(booking.start_date) }} – {{ formatDate(booking.end_date) }}</p>
                    <p class="text-sm text-base-content/70">{{ nights(booking) }} ночей</p>
                  </td>
                  <td>{{ booking.guests }}</td>
                  <td class="font-bold text-primary">{{ booking.total_price }} ₽</td>
                  <td>
                    <span
                      class="badge"
                      :class="{
                        'badge-success': booking.status === 'confirmed',
                        'badge-warning': booking.status === 'pending',
                        'badge-error': booking.status === 'cancelled'
                      }"
                    >
                      {{ statusLabel(booking.status) }}
                    </span>
                  </td>
                  <td>
                    <div class="flex gap-2">
                      <NuxtLink :to="`/house-show/${booking.house_id}`" class="btn btn-sm btn-ghost">
                        Подробнее
                      </NuxtLink>
                      <button v-if="booking.status === 'pending'" @click="cancelBooking(booking.id)" class="btn btn-sm btn-error">
                        Отменить
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  
      <!-- Модал отмены (опционально) -->
      <dialog id="cancel-modal" class="modal">
        <div class="modal-box">
          <h3 class="text-lg font-bold">Отменить бронирование?</h3>
          <p class="py-4">Это действие нельзя отменить.</p>
          <div class="modal-action">
            <button class="btn btn-error" @click="confirmCancel">Да, отменить</button>
            <form method="dialog" class="btn">Отмена</form>
          </div>
        </div>
      </dialog>
    </div>
  </template>
  
  <script setup lang="ts">
  import { format } from 'date-fns'
  import { ru } from 'date-fns/locale'
  
  // Состояние
  const { isAuthenticated, user } = useSanctumAuth()
  const config = useRuntimeConfig()
  const bookings = ref<any[]>([])
  const loading = ref(true)
  
  // Загрузка бронирований
  const fetchBookings = async () => {
    if (!isAuthenticated.value) {
      navigateTo('/login')
      return
    }
    try {
      bookings.value = await $fetch(`${config.public.apiBase}/api/user/bookings`, {
        credentials: 'include' // Для Sanctum CSRF
      })
    } catch (error) {
      console.error('Ошибка загрузки бронирований:', error)
      useToast().add({ title: 'Ошибка загрузки', color: 'error' })
    } finally {
      loading.value = false
    }
  }
  
  onMounted(fetchBookings)
  
  // Утилиты
  const formatDate = (dateStr: string) => format(new Date(dateStr), 'dd.MM.yyyy', { locale: ru })
  const nights = (booking: any) => {
    const diff = (new Date(booking.end_date).getTime() - new Date(booking.start_date).getTime()) / (1000 * 60 * 60 * 24)
    return Math.ceil(diff)
  }
  const statusLabel = (status: string) => {
    const labels = { pending: 'Ожидает', confirmed: 'Подтверждено', cancelled: 'Отменено' }
    return labels[status] || status
  }
  
  // Отмена бронирования (пример, добавь эндпоинт на backend)
  const cancelBooking = (id: number) => {
    // Открой модал
    const modal = document.getElementById('cancel-modal') as any
    modal?.showModal()
    // Здесь сохрани ID для отмены
  }
  
  const confirmCancel = async () => {
    // TODO: POST /api/user/bookings/{id}/cancel
    // await useSanctumClient()(`/api/user/bookings/${id}/cancel`, { method: 'POST' })
    // fetchBookings() // Перезагрузка
    useToast().add({ title: 'Бронирование отменено', color: 'success' })
    const modal = document.getElementById('cancel-modal') as any
    modal?.close()
  }
  </script>