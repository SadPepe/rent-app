<template>
  <form class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md space-y-4" @submit.prevent="submitForm">
    <h2 class="text-2xl font-bold mb-4">Заявка на аренду дома</h2>
    <div>
      <label class="block mb-1 font-medium">Ваше имя</label>
      <input v-model="form.name" type="text" class="input input-bordered w-full" required />
    </div>
    <div>
      <label class="block mb-1 font-medium">Телефон</label>
      <input v-model="form.phone" type="tel" class="input input-bordered w-full" required />
    </div>
    <div>
      <label class="block mb-1 font-medium">Дом</label>
      <select v-model="form.house" class="select select-bordered w-full" required>
        <option disabled value="">Выберите дом</option>
        <option v-for="house in houses" :key="house.id" :value="house.name">{{ house.name }}</option>
      </select>
    </div>
    <div class="flex gap-2">
      <div class="flex-1">
        <label class="block mb-1 font-medium">Дата заезда</label>
        <input v-model="form.dateFrom" type="date" class="input input-bordered w-full" required />
      </div>
      <div class="flex-1">
        <label class="block mb-1 font-medium">Дата выезда</label>
        <input v-model="form.dateTo" type="date" class="input input-bordered w-full" required />
      </div>
    </div>
    <button type="submit" class="btn btn-primary w-full">Отправить заявку</button>
    <div v-if="success" class="text-green-600 mt-2">Заявка успешно отправлена!</div>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const form = ref({
  name: '',
  phone: '',
  house: '',
  dateFrom: '',
  dateTo: '',
});

const houses = [
  { id: 1, name: 'Дом у озера' },
  { id: 2, name: 'Лесной коттедж' },
  { id: 3, name: 'Горная дача' },
];

const success = ref(false);

function submitForm() {
  // Здесь можно добавить отправку данных на сервер
  success.value = true;
  setTimeout(() => success.value = false, 3000);
  form.value = { name: '', phone: '', house: '', dateFrom: '', dateTo: '' };
}
</script>

<style scoped>
.input, .select {
  margin-bottom: 0.5rem;
}
</style>