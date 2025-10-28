<template>
  <div class="p-6 max-w-sm mx-auto">
    <h3 class="text-2xl font-bold mb-6 text-center">Вход</h3>
    <form @submit.prevent="handleLogin" class="space-y-4">
      <UInput v-model="email" type="email" placeholder="Email" required />
      <UInput v-model="password" type="password" placeholder="Пароль" required />
      <UButton type="submit" color="primary" block :loading="loading">Войти</UButton>
    </form>
    <p class="text-center mt-4 text-sm">
      Нет аккаунта?
      <a @click="$emit('switch-to-register')" class="link link-primary">Зарегистрироваться</a>
    </p>
  </div>
</template>

<script setup lang="ts">
const email = ref('')
const password = ref('')
const loading = ref(false)
const { login } = useSanctumAuth()

async function handleLogin() {
  loading.value = true
  try {
    await login({ email: email.value, password: password.value })
    $emit('success')
  } catch (e) {
    useToast().add({ title: 'Ошибка входа', color: 'error' })
  } finally {
    loading.value = false
  }
}
</script>