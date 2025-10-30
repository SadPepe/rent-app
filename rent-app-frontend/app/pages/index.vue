<template>
  <div class="min-h-screen bg-base-100">
    <!-- HERO SECTION -->
    <section class="py-20 bg-base-100">
  <div class="container mx-auto px-4 max-w-7xl">
    <div class="grid md:grid-cols-2 gap-12 items-center">

      <!-- Текст -->
      <div class="order-2 md:order-1 space-y-6">
        <h1 class="text-5xl font-bold text-primary leading-tight">
          Аренда уюта<br>в Подмосковье
        </h1>
        <p class="text-xl text-base-content/80">
          Всего <span class="text-primary font-semibold">2 эксклюзивных дома</span> — каждый с душой
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <NuxtLink to="#houses" class="btn btn-primary btn-lg shadow-xl">
            Посмотреть дома
          </NuxtLink>
          <NuxtLink to="#contact" class="btn btn-ghost text-base-content btn-lg">
            Связаться
          </NuxtLink>
        </div>
      </div>

      <!-- Фото -->
      <div class="order-1 md:order-2">
        <div class="relative group">
          <NuxtImg
            src="/img/hero.jpg"
            alt="Эксклюзивный дом"
            class="w-full h-96 md:h-full object-cover rounded-2xl shadow-2xl group-hover:shadow-3xl transition-shadow"
            loading="lazy"
          />
          <div class="absolute bottom-6 glass rounded-2xl p-4 left-6 text-white">
            <p class="text-lg font-semibold">Дом "Лесная сказка"</p>
            <p class="text-sm opacity-90">от 25 000 ₽ / ночь</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

    <!-- СПИСОК ДОМОВ -->
 <section id="houses" class="py-20 bg-base-200">
  <div class="container mx-auto px-4">
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold text-primary">Наши эксклюзивные дома</h2>
      <p class="text-lg text-base-content/70 mt-4">Всего 2 шедевра — выбирайте свой</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl mx-auto">
      <HouseCard v-for="house in houses" :key="house.id" :house="house" />
    </div>
  </div>
</section>

    <!-- ПРЕИМУЩЕСТВА -->
    <section class="py-20 bg-base-100 text-base-content">
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

</style>