<template>
  <div class="hero min-h-[50vh] bg-base-100">
    <!-- СЕКЦИЯ HERO -->
    <div class="hero-content flex-col lg:flex-row-reverse p-2">
      <img src="/img/hero.jpg" class="w-full max-w-sm rounded-lg shadow-2xl lg:max-w-lg" />
      <div class="text-center lg:text-left">
        <h1 class="text-3xl font-bold text-accent sm:text-5xl">
          Отдых на природе в уютных загородных домах
        </h1>
        <p class="py-6 text-secondary">
          Найдите идеальное место для отдыха от городской суеты
        </p>
        <button class="btn btn-primary">Выбрать место</button>
      </div>
    </div>
  </div>
  <!--СЕКЦИЯ С ДОМАМИ-->
  <div class="container mx-auto p-4">
    <h1 class="text-4xl font-bold mb-8 text-center">
      Найдите дом своей мечты
    </h1>

    <!-- Фильтры (если они есть) -->
    <!-- ... -->

    <!-- Обновленная сетка и карточки -->
    <div v-if="houses && houses.length" class="grid grid-cols-1 gap-8 md:grid-cols-2">
      <div
        v-for="house in houses"
        :key="house.id"
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
            <button class="btn btn-primary w-full">
              Подробнее
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Сообщение, если дома не найдены -->
    <div v-else class="text-center py-16">
      <p class="text-xl opacity-70">По вашему запросу ничего не найдено.</p>
    </div>
  </div>
  <!--СЕКЦИЯ О НАС-->
  <section class="bg-base-100 min-h-[50vh] flex items-center justify-center p-4">
    <section class="py-20 bg-base-200 max-w-screen-2xl">
      <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
          <div :class="{
            'opacity-100 translate-y-0': inView,
            'opacity-0 translate-y-8': !inView,
          }" class="transition-all duration-1000 ease-out">
            <h2 class="mb-4 text-4xl font-bold text-primary sm:text-5xl">
              Аренда загородных домов на природе
            </h2>
            <p class="mx-auto text-xl text-secondary">
              Мы помогаем вам найти идеальный дом для отдыха вдали от городской
              суеты. Наслаждайтесь чистым воздухом, красивыми пейзажами и уютом
              в лучших загородных домах.
            </p>
          </div>
        </div>
        <div class="items-center grid gap-16 mb-20 lg:grid-cols-2">
          <div class="space-y-8">
            <div :class="{
              'opacity-100 translate-y-0': inView,
              'opacity-0 translate-y-8': !inView,
            }" class="transition-all duration-1000 ease-out delay-200">
              <h3 class="mb-6 text-3xl font-bold text-primary">
                Ваш отдых начинается здесь
              </h3>
              <p class="mb-6 text-lg text-secondary leading-relaxed">
                С 2020 года мы предоставляем лучшие варианты аренды загородных
                домов для семейного отдыха, праздников и уединённых выходных.
                Более 500 довольных клиентов уже нашли свой идеальный дом с
                нашей помощью.
              </p>
              <p class="text-lg text-secondary leading-relaxed">
                Наши дома расположены в живописных местах, оснащены всем
                необходимым для комфортного проживания и отдыха на природе.
              </p>
            </div>
            <div :class="{
              'opacity-100 translate-y-0': inView,
              'opacity-0 translate-y-8': !inView,
            }" class="transition-all duration-1000 ease-out delay-200">
              <div class="space-y-4">
                <div class="items-start space-x-4 rtl:space-x-reverse flex">
                  <div class="shrink-0 items-center justify-center flex w-8 h-8 rounded-full bg-primary">
                    <svg class="w-4 h-4 text-secondary-content" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </div>
                  <div>
                    <h4 class="text-lg font-semibold text-accent">
                      Большой выбор домов
                    </h4>
                    <p class="text-secondary">
                      Более 100 объектов в разных регионах
                    </p>
                  </div>
                </div>
                <div class="items-start space-x-4 rtl:space-x-reverse flex">
                  <div class="shrink-0 items-center justify-center flex w-8 h-8 rounded-full bg-primary">
                    <svg class="w-4 h-4 text-secondary-content" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </div>
                  <div>
                    <h4 class="text-lg font-semibold text-accent">
                      Природа и комфорт
                    </h4>
                    <p class="text-secondary">
                      Все дома оборудованы для комфортного отдыха
                    </p>
                  </div>
                </div>
                <div class="items-start space-x-4 rtl:space-x-reverse flex">
                  <div class="shrink-0 items-center justify-center flex w-8 h-8 rounded-full bg-primary">
                    <svg class="w-4 h-4 text-secondary-content" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </div>
                  <div>
                    <h4 class="text-lg font-semibold text-accent">
                      Индивидуальный подход
                    </h4>
                    <p class="text-secondary">
                      Поможем подобрать дом под ваши пожелания
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="relative">
            <div :class="{
              'opacity-100 scale-100': inView,
              'opacity-0 scale-95': !inView,
            }" class="transition-all duration-1000 ease-out delay-300">
              <div class="relative overflow-hidden rounded-3xl shadow-2xl">
                <img
                  src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                  alt="Загородный дом" class="object-cover w-full h-96" />
                <div class="absolute bottom-6 left-6 right-6">
                  <div class="backdrop-blur-sm p-6 rounded-xl bg-white shadow-lg">
                    <div class="grid grid-cols-3 gap-4 text-center">
                      <div>
                        <div class="text-2xl font-bold text-accent">100+</div>
                        <div class="text-sm text-secondary-content">Домов</div>
                      </div>
                      <div>
                        <div class="text-2xl font-bold text-accent">500+</div>
                        <div class="text-sm text-secondary-content">
                          Клиентов
                        </div>
                      </div>
                      <div>
                        <div class="text-2xl font-bold text-accent">20+</div>
                        <div class="text-sm text-secondary-content">
                          Локаций
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";

// --- Метаданные страницы и пропсы ---
definePageMeta({
  layout: "guest",
});

defineProps<{
  prevIcon?: string;
  nextIcon?: string;
}>();

// --- Типы данных ---
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

// --- Переменные для анимаций и контента ---
const inView = ref(false);

// --- Реактивные переменные для домов ---
const houses = ref<House[]>([]);

// --- Хук жизненного цикла ---
onMounted(async () => {
  console.log("Fetching houses...");
  try {
    const response = await fetch("http://localhost:8000/api/houses");
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const responseData = await response.json();
    console.log("Полученные данные с сервера:", responseData);

    let housesData: House[] = [];

    if (responseData && Array.isArray(responseData.data)) {
      housesData = responseData.data;
    } else if (Array.isArray(responseData)) {
      housesData = responseData;
    } else {
      console.error("Unexpected data structure:", responseData);
    }

    console.log("Данные для присвоения в houses.value:", housesData);
    houses.value = housesData;
    console.log("Значение houses.value после присвоения:", houses.value);
  } catch (error) {
    console.error("Failed to fetch houses:", error);
  }
});
</script>

<style lang="scss" scoped>

</style>