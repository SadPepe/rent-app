<template>
  <div class="navbar bg-base-100 shadow-md sticky top-0 z-50">
    <!-- Логотип -->
    <div class="navbar-start">
      <NuxtLink to="/" class="btn btn-ghost text-2xl font-bold text-primary">
        WoodRent
      </NuxtLink>
    </div>

    <!-- Десктоп: меню -->
    <div class="navbar-center hidden md:flex">
      <ul class="menu menu-horizontal px-1 gap-2 text-base-content">
        <li><NuxtLink to="/" class="btn btn-ghost">Главная</NuxtLink></li>
        <li><NuxtLink to="/#houses" class="btn btn-ghost">Дома</NuxtLink></li>
        <li>
          <NuxtLink to="/#contact" class="btn btn-ghost">Контакты</NuxtLink>
        </li>
      </ul>
    </div>

    <!-- Десктоп: кнопки -->
    <div class="navbar-end hidden md:flex gap-2">
      <div class="flex items-center gap-2 text-primary">
        <button @click="setTheme('woodland')" class="btn btn-ghost btn-lg">
          <Icon name="i-heroicons-moon" />
        </button>
        <button
          @click="setTheme('woodland-light')"
          class="btn btn-ghost btn-lg"
        >
          <Icon name="i-heroicons-sun" />
        </button>
      </div>
      <ClientOnly>
        <template v-if="!isAuthenticated">
          <UButton
            @click="openLoginModal"
            class="btn-primary text-primary-content"
            >Войти</UButton
          >
          <UButton
            @click="openRegisterModal"
            class="btn-secondary text-primary-content"
            >Регистрация</UButton
          >
        </template>
        <template v-else>
          <div class="dropdown dropdown-end">
            <div
              tabindex="0"
              role="button"
              class="btn btn-ghost btn-circle avatar"
            >
              <div
                class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2"
              >
                <Icon name="i-heroicons-user-circle" class="w-10 h-10" />
              </div>
            </div>
            <ul
              tabindex="0"
              class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 mt-3"
            >
              <li><NuxtLink to="/profile">Профиль</NuxtLink></li>
              <li><a @click="handleLogout">Выйти</a></li>
            </ul>
          </div>
        </template>
      </ClientOnly>
    </div>

    <!-- Мобильное: кнопка меню -->
    <UDrawer
      v-model:open="mobileMenuOpen"
      direction="right"
      :ui="{ content: 'bg-base-300' }"
    >
      <div class="navbar-end md:hidden">
        <UButton
          icon="i-heroicons-bars-3"
          variant="ghost"
          size="sm"
          @click="mobileMenuOpen"
        />
      </div>
      <template #content>
        <label for="my-drawer" class="drawer-overlay"></label>
        <div class="pt-4 w-80 h-full bg-base-100 text-base-content">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">WoodRent</h3>
            <button
              @click="mobileMenuOpen = false"
              class="btn btn-ghost btn-sm"
            >
              <Icon name="i-heroicons-x-mark" class="w-6 h-6" />
            </button>
          </div>

          <ul class="space-y-2 mb-8">
            <li>
              <NuxtLink to="/" @click="mobileMenuOpen = false" class="text-lg"
                >Главная</NuxtLink
              >
            </li>
            <li>
              <NuxtLink
                to="/#houses"
                @click="mobileMenuOpen = false"
                class="text-lg"
                >Дома</NuxtLink
              >
            </li>
            <li>
              <NuxtLink
                to="/#contact"
                @click="mobileMenuOpen = false"
                class="text-lg"
                >Контакты</NuxtLink
              >
            </li>
          </ul>

          <ClientOnly>
            <div v-if="!isAuthenticated" class="mb-6"></div>
            <template v-if="!isAuthenticated">
              <div class="space-y-3">
                <UButton
                  @click="
                    openLoginModal();
                    mobileMenuOpen = false;
                  "
                  block
                  variant="ghost"
                  >Войти</UButton
                >
                <UButton
                  @click="
                    openRegisterModal();
                    mobileMenuOpen = false;
                  "
                  color="primary"
                  block
                  >Регистрация</UButton
                >
              </div>
            </template>
            <template v-else>
              <div class="space-y-3">
                <NuxtLink
                  to="/profile"
                  @click="mobileMenuOpen = false"
                  class="btn btn-ghost w-full justify-start"
                >
                  <Icon name="i-heroicons-user" class="w-5 h-5 mr-2" />
                  {{ user?.name || "Профиль" }}
                </NuxtLink>
                <UButton
                  @click="
                    handleLogout();
                    mobileMenuOpen = false;
                  "
                  color="error"
                  variant="ghost"
                  block
                  >Выйти</UButton
                >
              </div>
            </template>
          </ClientOnly>
        </div>
      </template>
      <!--
    <div class="">
      <label for="my-drawer" class="drawer-overlay"></label>
      <div class="menu p-6 w-80 h-full bg-base-100 text-base-content">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold">WoodRent</h3>
          <button @click="mobileMenuOpen = false" class="btn btn-ghost btn-sm">
            <Icon name="i-heroicons-x-mark" class="w-6 h-6" />
          </button>
        </div>

        
        <ul class="menu space-y-2 mb-8">
          <li><NuxtLink to="/" @click="mobileMenuOpen = false" class="text-lg">Главная</NuxtLink></li>
          <li><NuxtLink to="/#houses" @click="mobileMenuOpen = false" class="text-lg">Дома</NuxtLink></li>
          <li><NuxtLink to="/#contact" @click="mobileMenuOpen = false" class="text-lg">Контакты</NuxtLink></li>
        </ul>


        <ClientOnly>
          <div v-if="!isAuthenticated" class="divider mb-6"></div>
          <template v-if="!isAuthenticated">
            <div class="space-y-3">
              <UButton @click="openLoginModal(); mobileMenuOpen = false" block variant="ghost">Войти</UButton>
              <UButton @click="openRegisterModal(); mobileMenuOpen = false" color="primary" block>Регистрация</UButton>
            </div>
          </template>
          <template v-else>
            <div class="space-y-3">
              <NuxtLink to="/profile" @click="mobileMenuOpen = false" class="btn btn-ghost w-full justify-start">
                <Icon name="i-heroicons-user" class="w-5 h-5 mr-2" />
                {{ user?.name || 'Профиль' }}
              </NuxtLink>
              <UButton @click="handleLogout(); mobileMenuOpen = false" color="error" variant="ghost" block>Выйти</UButton>
            </div>
          </template>
        </ClientOnly>
      </div>
    </div>
  -->
    </UDrawer>
  </div>

  <!-- МОБИЛЬНОЕ МЕНЮ (Drawer) -->

  <!-- МОДАЛЬНОЕ ОКНО ВХОДА -->
  <UModal v-model:open="showLoginModal">
    <template #content>
      <div class="flex items-center justify-between">
        <h3
          class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
        >
          Вход в аккаунт
        </h3>
        <UButton
          color="gray"
          variant="ghost"
          icon="i-heroicons-x-mark-20-solid"
          class="-my-1"
          @click="showLoginModal = false"
        />
      </div>
      <form @submit.prevent="handleLogin" class="space-y-4 flex flex-col">
        <UInput
          v-model="loginForm.email"
          type="email"
          placeholder="Email"
          label="Email"
          required
        />
        <UInput
          v-model="loginForm.password"
          type="password"
          placeholder="Пароль"
          label="Пароль"
          required
        />

        <div class="flex justify-end gap-2">
          <UButton variant="ghost" @click="showLoginModal = false"
            >Отмена</UButton
          >
          <UButton type="submit" color="primary" :loading="loginLoading"
            >Войти</UButton
          >
        </div>
      </form>
      <p class="text-center mt-4 text-sm text-gray-500 dark:text-gray-400">
        Нет аккаунта?
        <button
          @click="switchToRegister"
          class="text-primary-500 dark:text-primary-400 hover:underline ml-1"
        >
          Зарегистрироваться
        </button>
      </p>
    </template>
  </UModal>

  <!-- МОДАЛЬНОЕ ОКНО РЕГИСТРАЦИИ -->
  <UModal v-model:open="showRegisterModal" class="p-6">
    <template #content>
      <div class="flex items-center justify-between">
        <h3
          class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
        >
          Регистрация
        </h3>
        <UButton
          color="primary"
          variant="ghost"
          icon="i-heroicons-x-mark-20-solid"
          class="-my-1"
          @click="showRegisterModal = false"
        />
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4 flex flex-col">
        <UInput
          v-model="registerForm.name"
          placeholder="Имя"
          label="Имя"
          required
        />
        <UInput
          v-model="registerForm.email"
          type="email"
          placeholder="Email"
          label="Email"
          required
        />
        <UInput
          v-model="registerForm.password"
          type="password"
          placeholder="Пароль"
          label="Пароль"
          required
        />
        <UInput
          v-model="registerForm.password_confirmation"
          type="password"
          placeholder="Подтвердите пароль"
          label="Подтвердите пароль"
          required
        />

        <div class="flex justify-end gap-2">
          <UButton variant="ghost" @click="showRegisterModal = false"
            >Отмена</UButton
          >
          <UButton type="submit" color="primary" :loading="registerLoading"
            >Зарегистрироваться</UButton
          >
        </div>
      </form>

      <p class="text-center mt-4 text-sm text-gray-500 dark:text-gray-400">
        Уже есть аккаунт?
        <button
          @click="switchToLogin"
          class="text-primary-500 dark:text-primary-400 hover:underline ml-1"
        >
          Войти
        </button>
      </p>
    </template>
  </UModal>
</template>

<script setup lang="ts">
// Состояние
const mobileMenuOpen = ref(false);
const showLoginModal = ref(false);
const showRegisterModal = ref(false);

// Формы
const loginForm = ref({
  email: "",
  password: "",
});

const registerForm = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

// Состояние загрузки
const loginLoading = ref(false);
const registerLoading = ref(false);

// Авторизация
const { isAuthenticated, user, login, logout } = useSanctumAuth();
const router = useRouter();

function setTheme(theme: string) {
  document.documentElement.setAttribute('data-theme', theme)
  localStorage.setItem('theme', theme)
}

onMounted(() => {
  const saved = localStorage.getItem('theme')
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  const theme = saved || (prefersDark ? 'woodland' : 'woodland-light')
  document.documentElement.setAttribute('data-theme', theme)
})

// Функции открытия модалок
function openLoginModal() {
  showLoginModal.value = true;
  showRegisterModal.value = false;
  mobileMenuOpen.value = false;
}

function openRegisterModal() {
  showRegisterModal.value = true;
  showLoginModal.value = false;
  mobileMenuOpen.value = false;
}

// Переключение между модалками
function switchToRegister() {
  openRegisterModal();
}

function switchToLogin() {
  openLoginModal();
}

// Обработка входа
async function handleLogin() {
  loginLoading.value = true;
  try {
    await login(loginForm.value);
    useToast().add({
      title: "Успешный вход!",
      color: "success",
    });
    showLoginModal.value = false;
    loginForm.value = { email: "", password: "" };
  } catch (error) {
    useToast().add({
      title: "Ошибка входа",
      description: "Проверьте email и пароль",
      color: "error",
    });
  } finally {
    loginLoading.value = false;
  }
}

// Обработка регистрации
async function handleRegister() {
  if (
    registerForm.value.password !== registerForm.value.password_confirmation
  ) {
    useToast().add({
      title: "Ошибка",
      description: "Пароли не совпадают",
      color: "error",
    });
    return;
  }

  registerLoading.value = true;
  try {
    const config = useRuntimeConfig();
    const client = useSanctumClient();

    await client(`${config.public.baseUrl}/api/register`, {
      method: "POST",
      body: {
        name: registerForm.value.name,
        email: registerForm.value.email,
        password: registerForm.value.password,
        password_confirmation: registerForm.value.password_confirmation,
      },
    });

    // Автоматический вход после регистрации
    await login({
      email: registerForm.value.email,
      password: registerForm.value.password,
    });

    useToast().add({
      title: "Регистрация успешна!",
      color: "success",
    });
    showRegisterModal.value = false;
    registerForm.value = {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    };
  } catch (error) {
    useToast().add({
      title: "Ошибка регистрации",
      description: "Попробуйте снова",
      color: "error",
    });
  } finally {
    registerLoading.value = false;
  }
}

// Выход
async function handleLogout() {
  await logout();
  router.push("/");
}
</script>
