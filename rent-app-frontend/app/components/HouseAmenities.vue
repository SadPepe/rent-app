<template>
  <div class="bg-base-200 rounded-2xl p-6 shadow-md">
    <h2 class="text-2xl font-bold text-primary mb-4">Удобства</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
      <div v-for="amenity in amenities" :key="amenity" class="flex items-center gap-2">
        <Icon name="i-heroicons-check-circle" class="w-5 h-5 text-success" />
        <span class="text-base-content">{{ amenity }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  amenities: string[]
}>()
</script>