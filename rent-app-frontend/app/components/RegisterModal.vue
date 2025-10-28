<template>
  <div class="p-6 max-w-sm mx-auto">
    <h3 class="text-2xl font-bold mb-6 text-center">Регистрация</h3>
    <form @submit.prevent="handleRegister" class="space-y-4">
      <UInput v-model="name" placeholder="Имя" required />
      <UInput v-model="email" type="email" placeholder="Email" required />
      <UInput v-model="password" type="password" placeholder="Пароль" required />
      <UInput v-model="password_confirmation" type="password" placeholder="Подтверждение" required />
      <UButton type="submit" color="primary" block :loading="loading">Создать аккаунт</UButton>
    </form>
    <p class="text-center mt-4 text-sm">
      Уже есть аккаунт?
      <a @click="$emit('switch-to-login')" class="link link-primary">Войти</a>
    </p>
  </div>
</template>

<script setup lang="ts">
const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const loading = ref(false)
const client = useSanctumClient()
const { login } = useSanctumAuth()

async function handleRegister() {
  loading.value = true
  try {
    await client('/api/register', {
      method: 'POST',
      body: { name: name.value, email: email.value, password: password.value, password_confirmation: password_confirmation.value }
    })
    await login({ email: email.value, password: password.value })
    $emit('success')
  } catch (e) {
    useToast().add({ title: 'Ошибка регистрации', color: 'error' })
  } finally {
    loading.value = false
  }
}
</script>