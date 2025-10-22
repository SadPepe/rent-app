<template>
  <div class="hero min-h-[50vh] bg-base-100">
  <div class="hero-content flex-col lg:flex-row-reverse">
    <img
      src="/img/hero.jpg"
      class="max-w-lg rounded-lg shadow-2xl"
    />
    <div>
      <h1 class="text-5xl font-bold text-accent">Отдых на природе в уютных загородных домах</h1>
      <p class="py-6 text-secondary">
        Найдите идеальное место для отдыха от городской суеты
      </p>
      <button class="btn btn-primary">Выбрать место</button>
    </div>
  </div>
</div>
<section class="py-20 bg-base-100">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <span class="text-secondary font-semibold uppercase tracking-wide text-sm">
             Наши преимущества
        </span>
        <h2 class="text-4xl md:text-5xl font-bold text-primary mt-2 mb-4">
          Отдых, который вы заслуживаете
        </h2>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
        <div 
          v-for="(benefit, index) in mainBenefits" 
          :key="benefit.id"
          class="feature-card bg-base-200"
          :class="getCardClass(index)"
        >
          <div class="feature-icon">
            <Icon :name="benefit.icon" />
          </div>
          <div>
            <h3 class="feature-title">{{ benefit.title }}</h3>
            <p class="feature-description">{{ benefit.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

<!--О нас-->
<div class="bg-base-100 min-h-[50vh] flex items-center justify-center p-4">
<section class="py-20 bg-base-200 max-w-screen-2xl">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <div :class="{ 'opacity-100 translate-y-0': inView, 'opacity-0 translate-y-8': !inView }" class="transition-all duration-1000 ease-out">
                <h2 class="mb-4 text-4xl font-bold text-primary sm:text-5xl">
                    Аренда загородных домов на природе
                </h2>
                <p class="mx-auto text-xl text-secondary">
                    Мы помогаем вам найти идеальный дом для отдыха вдали от городской суеты. Наслаждайтесь чистым воздухом, красивыми пейзажами и уютом в лучших загородных домах.
                </p>
            </div>
        </div>
        <div class="items-center grid gap-16 mb-20 lg:grid-cols-2">
            <div class="space-y-8">
                <div :class="{ 'opacity-100 translate-y-0': inView, 'opacity-0 translate-y-8': !inView }" class="transition-all duration-1000 ease-out delay-200">
                    <h3 class="mb-6 text-3xl font-bold text-primary">
                        Ваш отдых начинается здесь
                    </h3>
                    <p class="mb-6 text-lg text-secondary leading-relaxed">
                        С 2020 года мы предоставляем лучшие варианты аренды загородных домов для семейного отдыха, праздников и уединённых выходных. Более 500 довольных клиентов уже нашли свой идеальный дом с нашей помощью.
                    </p>
                    <p class="text-lg text-secondary leading-relaxed">
                        Наши дома расположены в живописных местах, оснащены всем необходимым для комфортного проживания и отдыха на природе.
                    </p>
                </div>
                <div :class="{ 'opacity-100 translate-y-0': inView, 'opacity-0 translate-y-8': !inView }" class="transition-all duration-1000 ease-out delay-200">
                    <div class="space-y-4">
                        <div class="items-start space-x-4 rtl:space-x-reverse flex">
                            <div class="flex-shrink-0 items-center justify-center flex w-[32px] h-[32px] rounded-full bg-primary">
                                <svg class="w-[16px] h-[16px] text-secondary-content" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
                            <div class="flex-shrink-0 items-center justify-center flex w-[32px] h-[32px] rounded-full bg-primary">
                                <svg class="w-[16px] h-[16px] text-secondary-content" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
                            <div class="flex-shrink-0 items-center justify-center flex w-[32px] h-[32px] rounded-full bg-primary">
                                <svg class="w-[16px] h-[16px] text-secondary-content" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
                <div :class="{ 'opacity-100 scale-100': inView, 'opacity-0 scale-95': !inView }" class="transition-all duration-1000 ease-out delay-300">
                    <div class="relative overflow-hidden rounded-3xl shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Загородный дом" class="object-cover w-full h-[384px]" />
                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="backdrop-blur-sm p-6 rounded-xl bg-white shadow-lg">
                                <div class="grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <div class="text-2xl font-bold text-accent">
                                            100+
                                        </div>
                                        <div class="text-sm text-secondary-content">
                                            Домов
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-accent">
                                            500+
                                        </div>
                                        <div class="text-sm text-secondary-content">
                                            Клиентов
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-accent">
                                            20+
                                        </div>
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
</div>

</template>

<script setup lang="ts">

definePageMeta({
  layout: "guest",
});

import { ref, onMounted } from 'vue';

const inView = ref(false);

onMounted(() => {
  setTimeout(() => {
    inView.value = true;
  }, 500);
});

const mainBenefits = [
  {
    id: 1,
    icon: 'mdi:home-search',
    title: 'Легкий поиск',
    description: 'Умная система подбора домов по вашим критериям'
  },
  {
    id: 2,
    icon: 'mdi:calendar-text',
    title: 'Мгновенное бронирование',
    description: 'Бронируйте онлайн без ожидания подтверждения'
  },
  {
    id: 3,
    icon: 'mdi:account-group',
    title: 'Поддержка 24/7',
    description: 'Наша команда всегда на связи для решения любых вопросов'
  },
  {
    id: 4,
    icon: 'mdi:map-marker-radius',
    title: 'Лучшие локации',
    description: 'Только живописные места с развитой инфраструктурой'
  }
]

const getCardClass = (index: number) => {
  const baseClasses = 'p-8 rounded-2xl flex items-start space-x-6 transition-all duration-500 hover:scale-105'
  
  const backgroundClasses = [
    'bg-secondary text-secondary-content hover:bg-primary',
    'bg-primary text-primary-content hover:bg-secondary text-secondary-content',
    'bg-primary text-primary-content hover:bg-secondary', 
    'bg-secondary text-secondary-content hover:bg-primary text-primary-content'
  ]
  
  return `${baseClasses} ${backgroundClasses[index]}`
}

interface Benefit {
  id: number
  icon: string
  title: string
  description: string
  features?: string[]
}

defineProps<{
  benefit: Benefit
}>()
</script>

<style scoped>

</style>