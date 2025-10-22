<template>
<div class="min-h-[450px]">
  <h4 class="block text-xl font-medium mb-4 text-slate-800">Вход</h4>

  <form @submit.prevent="handleLogin" class="">
    <div class="mb-1 flex flex-col gap-6">
    
      <div class="w-full min-w-[200px]">
        <label class="block mb-2 text-sm text-slate-600"> Email </label>
        <input
          v-model="formData.email"
          type="email"
          class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
          placeholder="Ваша почта"
        />
      </div>
      <div class="w-full min-w-[200px]">
        <label class="block mb-2 text-sm text-slate-600"> Пароль </label>
        <input
           v-model="formData.password"
          type="password"
          class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
        />
      </div>
    </div>

    <button
      class="mt-4 w-full rounded-md bg-slate-800 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
      type="submit"
    >
      Войти
    </button>
  </form>
  </div>
</template>

<script setup>
import { ref } from "vue";

const formData = ref({
  email: "",
  password: "",
});

const errors = ref({});
const { login } = useSanctumAuth()

const handleLogin = async () => {
  try {
    await login(formData.value);
  } catch (err) {
    errors.value = err.response._data.errors;
  }
};
</script>
