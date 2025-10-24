<template>
  <div class="bg-base-100 shadow-sm">
    <div class="navbar max-w-7xl mx-auto">
      <div class="md:hidden">
        <button popovertarget="popover-1" style="anchor-name: --anchor-1">
          <Icon name="meteor-icons:bars" size="24" />
        </button>

        <ul
          class="dropdown dropdown-start menu menu-lg w-52 rounded-box bg-base-100 shadow-sm"
          popover
          id="popover-1"
          style="position-anchor: --anchor-1"
        >
          <li><NuxtLink to="/" class="">Дома</NuxtLink></li>
          <li><NuxtLink to="/" class="">О проекте</NuxtLink></li>
          <li><NuxtLink to="/" class="">Контакты</NuxtLink></li>

          <template v-if="isAuthenticated">
            <li><NuxtLink to="/" class="">Профиль</NuxtLink></li>
            <li><button @click="handleLogout" class="">Выйти</button></li>
          </template>

          <template v-else>
            <li><NuxtLink to="/login" class="">Войти</NuxtLink></li>
            <li><NuxtLink to="/register" class="">Регистрация</NuxtLink></li>
          </template>
        </ul>
      </div>
      <div class="flex-1 flex justify-start">
        <a href="/" class="btn btn-ghost text-xl">daisyUI</a>
      </div>

      <div class="hidden md:flex flex-1 justify-center">
        <ul class="menu menu-horizontal px-1">
          <li><NuxtLink to="/" class="">Дома</NuxtLink></li>
          <li><NuxtLink to="/" class="">О проекте</NuxtLink></li>
          <li><NuxtLink to="/" class="">Контакты</NuxtLink></li>
        </ul>
      </div>
      <div class="flex flex-1 justify-end">
        <template v-if="!isAuthenticated">
          <a
            onclick="my_modal_2.showModal()"
            class="cursor-pointer hover:scale-105 transition duration-200"
          >
            Вход
            <div
              class="absolute -left-6 top-1/2 -translate-y-1/2 w-12 h-3 bg-zinc-100 blur-[18px] group-hover:scale-[3] opacity-0 group-hover:opacity-100 transition duration-1000"
            ></div>
          </a>
          <dialog id="my_modal_2" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box bg-base-100">
              <div class="flex justify-center mb-4 items-center gap-2">
                <span :class="!isRegisterForm ? 'text-primary font-bold' : 'text-accent'">Вход</span>
                <input
                  type="checkbox"
                  class="toggle"
                  v-model="isRegisterForm"
                />
                <span :class="isRegisterForm ? 'text-primary font-bold' : 'text-accent'"
                  >Регистрация</span
                >
              </div>
              <Signin class="min-h-[422px]" v-if="!isRegisterForm"></Signin>
              <Register v-if="isRegisterForm"></Register>
            </div>
            <form method="dialog" class="modal-backdrop">
            </form>
          </dialog>
        </template>

        <div class="hidden md:flex">
          <ul class="menu menu-horizontal px-1">
            <template v-if="isAuthenticated">
              <li><NuxtLink to="/profile" class="">Профиль</NuxtLink></li>
              <li><button @click="handleLogout" class="">Выйти</button></li>
            </template>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useRouter } from "vue-router";

const activeForm = ref<"login" | "register">("login"); // состояние для формы

const isRegisterForm = ref(false);

/**
 * Navbar component for загородные дома rental site.
 * - Адаптивный
 * - Проверка авторизации через useSanctumAuth
 * - Кнопки логин/регистрация или профиль/выйти
 * @component
 */
const mobileMenuOpen = ref(false);
const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const router = useRouter();
const auth = useSanctumAuth();
const isAuthenticated = computed(() => !!auth.user.value);

/**
 * Выход пользователя с обработкой ошибок и редиректом
 */
const handleLogout = async () => {
  try {
    await auth.logout();
    router.push("/login");
  } catch (error) {
    // TODO: добавить обработку ошибок (например, уведомление)
    console.error("Ошибка выхода:", error);
  }
};
</script>

<style scoped>
/* Можно добавить кастомные стили при необходимости */
</style>
