<template>
  <div class="min-h-screen bg-base-100">
    <!-- HERO SECTION -->
    <section class="hero min-h-[50vh] relative overflow-hidden">
      <!-- Фоновое изображение -->
      <div class="absolute inset-0">
        <NuxtImg
          src="/img/hero.jpg"
          alt="Загородный дом"
          class="w-full h-full object-cover"
          loading="lazy"
        />
        <!-- Тёмный оверлей -->
        <div class="absolute inset-0 bg-black/50"></div>
      </div>

      <!-- Контент -->
      <div class="hero-content text-center text-neutral-content relative z-10">
        <div class="max-w-3xl">
          <!-- Заголовок -->
          <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight text-primary-content">
            Аренда уютных домов<br>в Подмосковье
          </h1>

          <!-- Подзаголовок -->
          <p class="text-xl md:text-2xl mb-10 opacity-90 text-primary-content">
            Всего 3 эксклюзивных дома — каждый уникален
          </p>

          <!-- Кнопка CTA -->
          <NuxtLink to="#houses" class="btn btn-primary btn-md shadow-xl">
            Посмотреть дома
          </NuxtLink>
        </div>
      </div>

      <!-- Скролл вниз -->
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <Icon name="i-heroicons-chevron-down" class="w-8 h-8 text-white" />
      </div>
    </section>

    <!-- СПИСОК ДОМОВ -->
    <section id="houses" class="py-20 bg-base-200">
      <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12 text-primary">Наши дома</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
          <HouseCard v-for="house in houses" :key="house.id" :house="house" />
        </div>
      </div>
    </section>

    <!-- ПРЕИМУЩЕСТВА -->
    <section class="py-20 bg-base-300">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-12 text-primary">Почему выбирают нас</h2>
        <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-4">
              <Icon name="i-heroicons-home-modern" class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-xl font-semibold mb-2">Эксклюзивность</h3>
            <p class="text-base-content/70">Всего 3 дома — каждый уникален</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-4">
              <Icon name="i-heroicons-shield-check" class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-xl font-semibold mb-2">Безопасно</h3>
            <p class="text-base-content/70">Проверенные хозяева и отзывы</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-4">
              <Icon name="i-heroicons-clock" class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-xl font-semibold mb-2">Быстро</h3>
            <p class="text-base-content/70">Бронирование за 2 минуты</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const houses = ref([])

onMounted(async () => {
  try {
    houses.value = await $fetch('http://localhost:8000/api/houses')
  } catch (e) {
    console.error(e)
  }
})

function onSearch(data: any) {
  console.log('Поиск:', data)
  // navigateTo(`/rent?location=${data.location}&...`)
}
</script>

<style scoped>
.hero {
  background: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.7));
}
</style>