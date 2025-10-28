<template>
  <form @submit.prevent="handleSearch" class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <UInput
      v-model="form.location"
      placeholder="Куда едем?"
      icon="i-heroicons-map-pin"
      size="lg"
    />
    <UPopover>
      <UButton
        variant="outline"
        size="lg"
        icon="i-heroicons-calendar"
        class="justify-start text-left font-normal w-full"
      >
        <span v-if="dateRange">
          {{ formatDate(dateRange.start) }} — {{ formatDate(dateRange.end) }}
        </span>
        <span v-else>Даты</span>
      </UButton>
      <template #content>
        <UCalendar
          v-model="dateRange"
          :number-of-months="2"
          range
          class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg"
        />
      </template>
    </UPopover>
    <UInput
      v-model.number="form.guests"
      type="number"
      min="1"
      placeholder="Гостей"
      icon="i-heroicons-user-group"
      size="lg"
    />
    <UButton type="submit" color="primary" size="lg" class="w-full">
      Найти дома
    </UButton>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { format } from 'date-fns'

const form = ref({
  location: '',
  guests: 2
})

const dateRange = ref<{ start: Date | null; end: Date | null }>({
  start: null,
  end: null
})

const emit = defineEmits<{
  search: [data: { location: string; dates: any; guests: number }]
}>()

function formatDate(date: Date | null) {
  if (!date) return ''
  return format(date, 'd MMM')
}

function handleSearch() {
  emit('search', {
    location: form.value.location,
    dates: dateRange.value,
    guests: form.value.guests
  })
}
</script>