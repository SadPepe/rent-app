<template>
  <div class="bg-base-200 rounded-2xl p-6 shadow-md">
    <h2 class="text-2xl font-bold text-primary mb-4">Описание</h2>
    <p class="text-base-content leading-relaxed whitespace-pre-wrap">{{ text }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  text: string
}>()
</script>