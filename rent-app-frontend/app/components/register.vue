<template>
  <div class="min-h-[450px]">
    <h4 class="block text-xl font-bold text-center text-base-content">
      Регистрация
    </h4>

    <form @submit.prevent="handleRegister" class="">
      <div class="flex flex-col">
        <fieldset class="fieldset">
          <legend class="fieldset-legend text-lg">Имя</legend>
          <input
            v-model="formData.name"
            type="text"
            class="input w-full"
            placeholder="Type here"
          />
        </fieldset>
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
        <fieldset class="fieldset">
          <legend class="fieldset-legend text-lg">Подтвердите пароль</legend>
          <input
            v-model="formData.password_confirmation"
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
        Зарегестрироваться
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";

const formData = ref({
  email: "",
  name: "",
  password_confirmation: "",
  password: "",
});

const client = useSanctumClient();

const { login } = useSanctumAuth();

const config = useRuntimeConfig();

const handleRegister = async () => {
  await client(`${config.public.baseUrl}/api/register`, {
    method: "POST",
    body: JSON.stringify(formData.value),
  });

  await login({
    email: formData.value.email,
    password: formData.value.password,
  });
};
</script>
