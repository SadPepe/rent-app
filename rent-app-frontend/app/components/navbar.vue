<template>
  <!-- ====================== DAISYUI DRAWER ====================== -->
  <div class="drawer drawer-end fixed top-0 z-50">
    <!-- чекбокс, который управляет открытием -->
    <input id="mobile-drawer" type="checkbox" class="drawer-toggle" :checked="mobileMenuOpen"
      @change="mobileMenuOpen = $event.target.checked" />

    <!-- ====================== ОСНОВНОЙ КОНТЕНТ ====================== -->
    <div class="drawer-content">
      <header class="sticky top-0 z-50 transition-all duration-300"
      :class="{
        'bg-base-100/95 backdrop-blur-sm': isScrolled,
        'bg-transparent': !isScrolled
      }">
        <div class="mx-auto max-w-7xl">
          <div class="flex items-center justify-between h-14 px-2">
            <!-- ЛОГОТИП -->
            <NuxtLink to="/" class="flex items-center">
              <div class="w-10 h-10 rounded-full flex bg-primary items-center justify-center mr-2">
                <Icon name="i-heroicons-home-modern" size="26" class="text-primary-content" />
              </div>
              <span class="text-xl font-bold"
              :class="{'text-accent': !isScrolled,
                'text-primary': isScrolled
              }">WoodRent</span>
            </NuxtLink>

            <!-- ДЕСКТОП: НАВИГАЦИЯ -->
            <nav class="hidden lg:flex items-center gap-8">
              <NuxtLink v-for="link in navLinks" :key="link.to" :to="link.to"
                class="text-base font-medium text-base-content/80 hover:text-primary transition-colors"
                active-class="text-primary font-semibold">
                {{ link.label }}
              </NuxtLink>
            </nav>

            <!-- ДЕСКТОП: ДЕЙСТВИЯ -->
            <div class="hidden lg:flex items-center gap-3">
              <!-- Темы -->
              <label class="swap swap-rotate p-2 rounded-full hover:bg-base-200 transition-colors cursor-pointer"
                :class="{ 'swap-active': isDark }">
                <!-- скрытый чекбокс -->
                <input type="checkbox" :checked="isDark" @change="toggleTheme" />

                <!-- Солнце (светлая тема) -->
                <svg class="swap-on h-6 w-6 fill-current text-yellow-500" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24">
                  <path
                    d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
                </svg>

                <!-- Луна (тёмная тема) -->
                <svg class="swap-off h-6 w-6 fill-current text-blue-400" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24">
                  <path
                    d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
                </svg>
              </label>

              <!-- Авторизация -->
              <ClientOnly>
                <template v-if="!isAuthenticated">
                  <label class="btn btn-primary" @click="openAuthModal('login')">Войти</label>
                </template>
                <template v-else>
                  <button popovertarget="popover-1" style="anchor-name: --anchor-1"
                    class="flex items-center gap-2 p-2 rounded-full hover:bg-base-200">
                    <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center">
                      <Icon name="i-heroicons-user" class="w-5 h-5 text-primary" />
                    </div>
                    <span class="text-sm font-medium text-base-content">{{ user?.name }}</span>
                  </button>
                  <ul class="dropdown dropdown-end menu menu-md w-52 space-y-2 rounded-box bg-base-200 shadow-sm"
                    popover id="popover-1" style="position-anchor: --anchor-1">
                    <li><NuxtLink to="/profile" class="text-base-content">
                        <Icon name="i-heroicons-user" />профиль
                    </NuxtLink></li>
                    <li>
                      <button @click="handleLogout" class="text-base-content">
                        <Icon name="i-heroicons-arrow-left-start-on-rectangle" />
                        выход
                      </button>
                    </li>
                  </ul>
                </template>
              </ClientOnly>
            </div>

            <!-- МОБИЛЬНАЯ КНОПКА МЕНЮ -->
            <label for="mobile-drawer" aria-label="open sidebar" class="lg:hidden btn btn-ghost">
              <Icon name="i-heroicons-bars-3" class="text-primary" size="28" />
            </label>
          </div>
        </div>
      </header>
    </div>

    <!-- ====================== БОКОВАЯ ПАНЕЛЬ (мобильное меню) ====================== -->
    <div class="drawer-side z-50">
      <label for="mobile-drawer" aria-label="close sidebar" class="drawer-overlay"></label>

      <div class="p-6 min-h-full bg-base-200 w-80 flex flex-col">
        <!-- Заголовок -->
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl text-primary font-bold">Меню</h3>
          <label for="mobile-drawer" class="btn btn-ghost btn-sm">
            <Icon name="i-heroicons-x-mark" class="w-6 h-6" />
          </label>
        </div>

        <!-- Навигация -->
        <nav class="space-y-4 mb-4">
          <NuxtLink v-for="link in navLinks" :key="link.to" :to="link.to" @click="mobileMenuOpen = false"
            class="block text-lg font-medium text-base-content/80 hover:text-primary">
            {{ link.label }}
          </NuxtLink>
        </nav>

        <!-- Авторизация в мобильном меню -->
        <div class="pt-6 border-t space-y-3">
          <ClientOnly>
            <template v-if="!isAuthenticated">
              <label for="mobile-drawer" class="btn btn-primary w-full text-primary-content"
                @click="openAuthModal('login')">Войти</label>
            </template>
            <template v-else>
              <div class="flex flex-col">
                <NuxtLink to="/profile" class="btn btn-info text-info-content">
                  <Icon name="i-heroicons-user" />профиль
                </NuxtLink>

                <button @click="handleLogout" class="btn btn-error text-error-content">
                  <Icon name="i-heroicons-arrow-left-start-on-rectangle" />
                  выход
                </button>
              </div>
            </template>
          </ClientOnly>
        </div>
      </div>
    </div>
  </div>

  <!-- ====================== ЕДИНОЕ МОДАЛЬНОЕ ОКНО ====================== -->
  <dialog :open="isAuthModalOpen" class="modal">
    <div class="modal-box text-base-content max-w-md">
      <button @click="closeAuthModal" class="btn btn-sm btn-circle btn-ghost text-primary absolute right-2 top-2">
        X
      </button>

      <!-- ВХОД -->
      <div v-if="authMode === 'login'">
        <h3 class="text-2xl font-bold text-center mb-6">Вход</h3>
        <form @submit.prevent="handleLogin" class="space-y-4">
          <input v-model="loginForm.email" type="email" placeholder="Email" class="input input-bordered w-full"
            required />
          <input v-model="loginForm.password" type="password" placeholder="Пароль" class="input input-bordered w-full"
            required />
          <button type="submit" class="btn btn-primary w-full" :disabled="loginLoading">
            {{ loginLoading ? "Вход..." : "Войти" }}
          </button>
        </form>
        <p class="text-center mt-4 text-sm">
          Нет аккаунта?
          <button @click="switchTo('register')" class="link link-primary">
            Зарегистрироваться
          </button>
        </p>
      </div>

      <!-- РЕГИСТРАЦИЯ -->
      <div v-else>
        <h3 class="text-2xl font-bold text-center mb-6">Регистрация</h3>
        <form @submit.prevent="handleRegister" class="space-y-4">
          <input v-model="registerForm.name" type="text" placeholder="Имя" class="input input-bordered w-full"
            required />
          <input v-model="registerForm.email" type="email" placeholder="Email" class="input input-bordered w-full"
            required />
          <input v-model="registerForm.password" type="password" placeholder="Пароль"
            class="input input-bordered w-full" required />
          <input v-model="registerForm.password_confirmation" type="password" placeholder="Подтвердите пароль"
            class="input input-bordered w-full" required />
          <button type="submit" class="btn btn-primary w-full" :disabled="registerLoading">
            {{ registerLoading ? "Регистрация..." : "Зарегистрироваться" }}
          </button>
        </form>
        <p class="text-center mt-4 text-sm">
          Уже есть аккаунт?
          <button @click="switchTo('login')" class="link link-primary">
            Войти
          </button>
        </p>
      </div>
    </div>

    <!-- фон (закрытие по клику вне) -->
    <form method="dialog" class="modal-backdrop">
      <button @click="closeAuthModal">close</button>
    </form>
  </dialog>
</template>

<script setup lang="ts">
// Состояние
const mobileMenuOpen = ref(false);

const isDark = ref(false);

const isAuthModalOpen = ref(false);
const authMode = ref<"login" | "register">("login");
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

const isScrolled = ref(false);

function openAuthModal(mode: "login" | "register" = "login") {
  authMode.value = mode;
  isAuthModalOpen.value = true;
}

function closeAuthModal() {
  isAuthModalOpen.value = false;
  // Сброс форм
  loginForm.value = { email: "", password: "" };
  registerForm.value = {
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  };
}

function switchTo(mode: "login" | "register") {
  authMode.value = mode;
}

// Обработчики
async function handleLogin() {
  loginLoading.value = true;
  try {
    await login(loginForm.value);
    useToast().add({ title: "Успешный вход!", color: "success" });
    closeAuthModal();
  } catch {
    useToast().add({ title: "Ошибка входа", color: "error" });
  } finally {
    loginLoading.value = false;
  }
}

async function handleRegister() {
  if (
    registerForm.value.password !== registerForm.value.password_confirmation
  ) {
    useToast().add({ title: "Пароли не совпадают", color: "error" });
    return;
  }

  registerLoading.value = true;
  try {
    const client = useSanctumClient();
    await client("/api/register", {
      method: "POST",
      body: registerForm.value,
    });
    await login({
      email: registerForm.value.email,
      password: registerForm.value.password,
    });
    useToast().add({ title: "Регистрация успешна!", color: "success" });
    closeAuthModal();
  } catch {
    useToast().add({ title: "Ошибка регистрации", color: "error" });
  } finally {
    registerLoading.value = false;
  }
}

// Авторизация
const { isAuthenticated, user, login, logout } = useSanctumAuth();
const router = useRouter();

function toggleTheme() {
  isDark.value = !isDark.value;
  const theme = isDark.value ? "dark" : "light";
  document.documentElement.setAttribute("data-theme", theme);
  localStorage.setItem("theme", theme);
}

const navLinks = [
  { label: "главная", to: "/" },
  { label: "дома", to: "#houses" },
  { label: "контакты", to: "#contact" },
  { label: "Профиль", to: "/profile" },
];

const userMenu = [
  { label: "Профиль", to: "/profile" },
  { label: "Выйти", click: () => useSanctumAuth().logout() },
];

onMounted(() => {
  const handleScroll = () => {
    isScrolled.value = window.scrollY > 10; // Прозрачный только если в самом верху
  };

  window.addEventListener('scroll', handleScroll);
  handleScroll(); // Проверяем сразу при монтировании

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });

  const saved = localStorage.getItem("theme");
  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  const theme = saved || (prefersDark ? "dark" : "light");
  isDark.value = theme === "dark";
  document.documentElement.setAttribute("data-theme", theme);
});

async function handleLogout() {
  await logout();
  router.push("/");
}
</script>
