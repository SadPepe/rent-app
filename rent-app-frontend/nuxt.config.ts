// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  compatibilityDate: "2025-07-15",
  devtools: { enabled: true },

  vite: {
    plugins: [tailwindcss()],
  },

  css: ["./app/tailwind.css"],
  modules: ["nuxt-auth-sanctum", "@nuxt/icon", "@nuxt/image", "@nuxt/ui", '@nuxt/fonts'],
 fonts: {
    families: [
      { name: 'Inter', provider: 'google' },
    ]
  },
  image: {
    // Регистрируем наш новый кастомный провайдер
    providers: {
      backend: {
        name: 'backend', // Имя для нашего провайдера
        provider: '~/providers/backend.ts', // Путь к файлу, который мы создали
        options: {
          // Базовый URL для всех изображений
          baseURL: 'http://localhost:8000/storage/'
        }
      }
    },
    // Устанавливаем наш провайдер как провайдер по умолчанию
    provider: 'backend',
  },
  sanctum: {
    baseUrl: "http://localhost:8000",
    endpoints: {
      login: "/api/login",
      logout: "/api/logout",
    },
    redirect: {
      onLogin: "/",
      onLogout: "/login",
    },
  },
  runtimeConfig: {
    public: {
      apiBaseUrl:'http://localhost:8000'
  }
},
})
