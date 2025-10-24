<template>
  <div class="min-h-[450px]">
    <h4 class="block text-xl font-bold text-center text-base-content">Вход</h4>

    <form @submit.prevent="handleLogin" class="">
      <div class="flex flex-col">
        <fieldset class="fieldset">
          <legend class="fieldset-legend text-lg">Email</legend>
          <input
            v-model="formData.email"
            type="email"
            class="input w-full"
            placeholder="Type here"
          />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend text-lg">Пароль</legend>
          <input
            v-model="formData.password"
            type="password"
            class="input w-full"
            placeholder="Type here"
          />
        </fieldset>
      </div>

      <button
        class="bg-neutral btn text-neutral-content mt-2 w-full hover:bg-neutral-content hover:text-neutral"
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
const { login } = useSanctumAuth();

const handleLogin = async () => {
  try {
    await login(formData.value);
  } catch (err) {
    errors.value = err.response._data.errors;
  }
};
</script>
