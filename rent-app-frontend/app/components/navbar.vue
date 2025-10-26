<template>
 <div class="navbar bg-base-200 text-base-content">
    <!-- Начало Navbar -->
    <div class="navbar-start">
      <!-- Мобильный сайдбар -->
      <div class="block md:hidden">
        
        <UDashboardSidebar v-model:open="isSidebarOpen" mode="drawer">
          <template #header>
            <NuxtLink to="/" @click="isSidebarOpen = false">
              <!-- Здесь ваш логотип -->
              <span class="text-xl font-bold">YourLogo</span>
            </NuxtLink>
          </template>

          <!-- Навигация -->
          <UNavigationMenu :items="items" orientation="vertical" />

          <UDivider class="my-4" />

          <!-- Блок входа/регистрации -->
          <div v-if="!isAuthenticated" class="p-4">
            <div class="flex justify-center mb-4 items-center gap-2">
              <span
                :class="
                  !isRegisterForm ? 'text-primary font-bold' : 'text-accent'
                "
                >Вход</span
              >
              <input
                type="checkbox"
                class="toggle"
                v-model="isRegisterForm"
              />
              <span
                :class="isRegisterForm ? 'text-primary font-bold' : 'text-accent'"
                >Регистрация</span
              >
            </div>
            <Signin v-if="!isRegisterForm" @success="isSidebarOpen = false" />
            <Register v-if="isRegisterForm" @success="isSidebarOpen = false" />
          </div>

          <!-- Профиль пользователя -->
          <div v-if="isAuthenticated" class="p-4">
        
            <UButton to="/profile" block class="mt-2" @click="isSidebarOpen = false">Профиль</UButton>
            <!-- Здесь можно добавить кнопку выхода -->
          </div>
        </UDashboardSidebar>
      </div>

      <!-- Логотип для десктопа -->
      <NuxtLink to="/" class="btn btn-ghost text-3xl"
        >Rent-Wood</NuxtLink
      >
    </div>

    <!-- Центр Navbar (меню для десктопа) -->
    <div class="navbar-center hidden md:flex">
      <ul class="menu menu-horizontal px-1 text-xl">
        <li><NuxtLink to="/">Главная</NuxtLink></li>
        <li><NuxtLink to="/rent">Аренда</NuxtLink></li>
      </ul>
    </div>

    <!-- Конец Navbar -->
    <div class="navbar-end">
      
       <ClientOnly>
        <!-- Кнопка входа для десктопа (если нужна) -->
        <template v-if="!isAuthenticated">
           <!-- Можно оставить кнопку, открывающую модальное окно для десктопа, или сделать отдельную страницу входа -->
           <a class="btn btn-primary">Вход</a>
        </template>
        <!-- Аватар пользователя для десктопа -->
        <template v-if="isAuthenticated">
          <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
              <div class="w-10 rounded-full">
                <img alt="User Avatar" src="#" />

              </div>
            </div>
            <ul tabindex="0" class="mt-3 z-1 p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
              <li><NuxtLink to="/profile">Профиль</NuxtLink></li>
              <li><a>Выход</a></li>
            </ul>
          </div>
        </template>
      </ClientOnly>
      <UButton
          icon="i-heroicons-bars-3"
          class="btn btn-primary  text-primary-content aspect-square mx-2 flex md:hidden"
          variant="ghost"
          @click="isSidebarOpen = true"
        />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useRouter } from "vue-router";

const isSidebarOpen = ref(false);
const isRegisterForm = ref(false);

// Элементы для навигационного меню
const items = ref([
  [{ label: "Главная", to: "/" }],
  [{ label: "Аренда", to: "/rent" }],
]);

const activeForm = ref<"login" | "register">("login"); // состояние для формы



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
