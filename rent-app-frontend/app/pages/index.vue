<template>
  <div 
  class="hero min-h-screen relative overflow-hidden"
  style="background-image: url(/img/hero.jpg); background-size: cover; background-position: center; background-attachment: fixed;"
>
  <div class="absolute inset-0 bg-linear-to-b from-black/70 to-transparent"></div>
  <div class="hero-overlay bg-black/60"></div>
  <div class="hero-content text-neutral-content text-center relative z-10 pt-20 md:pt-0">
    <div class="max-w-md">
      <h1 class="mb-5 text-3xl md:text-5xl font-bold text-left md:text-center">WoodRent</h1>
      <p class="mb-5 text-md md:text-lg text-left md:text-center">
        это уникальное место в 40 минутах от Набережных Челнов...
      </p>
      <NuxtLink to="#houses" class="btn btn-primary btn-lg shadow-xl">
        Посмотреть дома
      </NuxtLink>
    </div>
  </div>
</div>
    <!-- HERO SECTION -->
    <!--  <section class="py-20 bg-base-100">
  <div class="container mx-auto px-4 max-w-7xl">
    <div class="grid md:grid-cols-2 gap-12 items-center">

      <div class="order-2 md:order-1 space-y-6">
        <h1 class="text-2xl md:text-5xl font-bold text-primary leading-tight">
          Аренда уюта<br>в Подмосковье
        </h1>
        <p class="text-md md:text-xl text-base-content/80">
          Всего <span class="text-primary font-semibold">2 эксклюзивных дома</span> — каждый с душой
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <NuxtLink to="#houses" class="btn btn-primary btn-md md:btn-lg shadow-xl">
            Посмотреть дома
          </NuxtLink>
          <NuxtLink to="#contact" class="btn btn-ghost text-base-content btn-md md:btn-lg">
            Связаться
          </NuxtLink>
        </div>
      </div>

      <div class="order-1 md:order-2">
        <div class="relative group">
          <NuxtImg
            src="/img/hero.webp"
            alt="Эксклюзивный дом"
            class="w-full h-96 md:h-96 object-cover rounded-2xl shadow-2xl group-hover:shadow-3xl transition-shadow"
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
-->

    <!-- СПИСОК ДОМОВ -->
    <section id="houses" class="py-20 bg-base-200">
      <div class="container mx-auto px-4">
        <!--
        <div class="text-center mb-12">
          <h2 class="text-2xl md:text-4xl font-bold text-primary">Наши эксклюзивные дома</h2>
          <p class="text-md md:text-lg text-base-content/70 mt-4">Всего 2 шедевра — выбирайте свой</p>
        </div>
        -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 max-w-5xl mx-auto">
          <HouseCard v-for="house in houses" :key="house.id" :house="house" />
        </div>
      </div>
    </section>

    <div class="t1032__content" style="border-color: #354d30;border-width: 1px;">
      <div class="t1032__wrapper" style="height: auto;">
        <div class="t-card__title t-name t-name_xl" field="li_title__6610515499060" style="height: auto;">
          <div style="font-size: 18px;" data-customstyle="yes"><strong
              style="color: rgb(53, 77, 48); font-weight: 700; font-family: Montserrat;">ПРАВИЛА БРОНИРОВАНИЯ</strong>
          </div>
        </div>
        <div class="t-card__descr t-text" field="li_descr__6610515499060" style="height: auto;">
          <p style="text-align: left;"><span
              style="font-family: Montserrat; font-weight: 500; font-size: 14px; color: rgb(53, 77, 48);">/ заезд с
              15:00; </span></p>
          <p style="text-align: left;"><span
              style="font-family: Montserrat; font-weight: 500; font-size: 14px; color: rgb(53, 77, 48);">/ выезд до
              12:00; </span></p>
          <p style="text-align: left;"><span
              style="font-family: Montserrat; font-weight: 500; font-size: 14px; color: rgb(53, 77, 48);">/ бронирование
              при 100% оплате;</span></p><br>
          <p style="text-align: left;"><span
              style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">Ранее чем за 2 дня до
              заезда</span></p>
          <p style="text-align: left;"><span style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">—
              Отмена — без потери предоплаты</span></p>
          <p style="text-align: left;"><span style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">—
              Перенос возможен (не более 1 раза)</span></p><br>
          <p style="text-align: left;"><span
              style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">За 1 день до заезда</span></p>
          <p style="text-align: left;"><span style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">—
              Отмена невозможна</span></p>
          <p style="text-align: left;"><span style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">—
              Возможен только перенос (1 раз)</span></p><br>
          <p style="text-align: left;"><span style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">В
              день заезда</span></p>
          <p style="text-align: left;"><span style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">—
              Отмена и перенос невозможны</span></p>
          <p style="text-align: left;"><span style="font-family: Montserrat; font-size: 14px; color: rgb(53, 77, 48);">—
              Предоплата не возвращается</span></p>
        </div>
      </div>
    </div>
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
</template>

<script setup lang="ts">
const houses = ref([])
const config = useRuntimeConfig()
onMounted(async () => {
  try {
    houses.value = await $fetch(`${config.public.apiBase}/api/houses`)
  } catch (e) {
    console.error(e)
  }
})

</script>

<style scoped></style>