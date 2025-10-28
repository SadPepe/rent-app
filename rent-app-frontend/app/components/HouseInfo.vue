<template>
  <div class="bg-base-200 rounded-2xl p-6 shadow-md">
    <h1 class="text-3xl font-bold text-primary mb-3">{{ house.name }}</h1>
    <p class="text-lg text-base-content/80 flex items-center gap-2 mb-4">
      <Icon name="i-heroicons-map-pin" class="w-5 h-5" />
      {{ house.address }}
    </p>

    <div class="flex flex-wrap gap-4 text-lg">
      <div class="flex items-center gap-2">
        <Icon name="i-heroicons-bed" class="w-6 h-6 text-primary" />
        <span>{{ house.bedrooms }} спальни</span>
      </div>
      <div class="flex items-center gap-2">
        <Icon name="i-heroicons-bath" class="w-6 h-6 text-primary" />
        <span>{{ house.bathrooms }} ванные</span>
      </div>
      <div class="flex items-center gap-2">
        <Icon name="i-heroicons-user-group" class="w-6 h-6 text-primary" />
        <span>до {{ house.max_guests }} гостей</span>
      </div>
    </div>

    <div class="mt-4">
      <span class="text-3xl font-bold text-primary">{{ house.price_per_day }} ₽</span>
      <span class="text-base-content/70"> / ночь</span>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  house: any
}>()
</script>