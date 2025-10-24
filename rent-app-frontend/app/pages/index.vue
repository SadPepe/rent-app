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
  <section class="mb-12 max-w-7xl mx-auto">

    <h2 class="text-3xl font-bold mb-6 text-center">Наши дома</h2>
    <div v-if="houses && houses.length" class="flex flex-col gap-8">
      <div
        v-for="house in houses"
        :key="house.id"
        class="card lg:card-side bg-base-200 shadow-sm"
      >
        <Swiper
          v-if="house.photos && house.photos.length"
          class="w-full lg:w-1/2 h-64 lg:h-auto"
          :slides-per-view="1"
          loop
          navigation
          :pagination="{ clickable: true }"
          :autoplay="{
            delay: 5000,
            disableOnInteraction: true,
          }"
          effect="creative"
          :creative-effect="{
            prev: {
              shadow: true,
              translate: [0, 0, -400],
            },
            next: {
              translate: ['100%', 0, 0],
            },
          }"
        >
          <SwiperSlide v-for="(photo, index) in house.photos" :key="index">
            <img :src="photo" :alt="`Photo of ${house.name} ${index + 1}`" class="object-cover w-full h-full" />
          </SwiperSlide>
        </Swiper>
        <figure v-else class="w-full lg:w-1/2 h-64 lg:h-auto bg-gray-300 flex items-center justify-center">
          <span class="text-gray-500">No image</span>
        </figure>

        <!-- Информация -->
        <div class="card-body">
          <h2 class="card-title">{{ house.name }}</h2>
          <h3 class="text-2xl font-bold mb-2 text-base-content">
            {{ house.name }}
          </h3>
          <p class="text-base-content mb-4">{{ house.description }}</p>
          <p class="text-base-content mb-4">{{ house.address }}</p>
          <p class="text-base-content mb-4">Спальни: {{ house.bedrooms }}</p>
          <p class="text-base-content mb-4">
            Ванные комнаты: {{ house.bathrooms }}
          </p>
          <p class="text-base-content mb-4">Гостей: {{ house.max_guests }}</p>

          <div class="card-actions justify-end">
            <span class="text-xl font-semibold text-accent">{{ house.price_per_day }}</span>
            <button class="btn btn-primary text-primary-content">
              Забронировать
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      <p class="text-center text-secondary">
        Загрузка домов...
      </p>
      <p class="text-center text-sm text-base-content">
        (Количество загруженных домов: {{ houses.length }})
      </p>
    </div>
  </section>

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

<style lang="scss" scoped></style>