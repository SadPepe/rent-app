<script setup lang="ts">
const nuxtApp = useNuxtApp()
const { activeHeadings, updateHeadings } = useScrollspy()
const auth = useSanctumAuth()
const { logout } = useSanctumAuth()
const isAuthenticated = computed(() => !!auth.user.value)

const items = computed(() => {
  const baseItems = [


  ]

  // Добавляем кнопки только если пользователь НЕ авторизован
  if (!isAuthenticated.value) {
    baseItems.push(
      {
        label: 'Login',
        to: '/login',
        active: activeHeadings.value.includes('login') && !activeHeadings.value.includes('pricing')
      },
      {
        label: 'Register',
        to: '/register',
        active: activeHeadings.value.includes('register') && !activeHeadings.value.includes('pricing')
      }
    )
  } else {
    baseItems.push(
      {
        label: 'Logout',
        onClick: handleLogout, // или используйте обработчик click для выхода
        active: false
      }
    )
  }

  return baseItems
})

const handleLogout = async () => {
  await logout()
}

nuxtApp.hooks.hookOnce('page:finish', () => {
  updateHeadings([
    document.querySelector('#features'),
    document.querySelector('#pricing'),
    document.querySelector('#testimonials')
  ].filter(Boolean) as Element[])
})
</script>

<template>
  <UHeader>
    <template #left>
      <NuxtLink to="/">
        <AppLogo class="w-auto h-6 shrink-0" />
      </NuxtLink>

      <TemplateMenu />
    </template>

    <template #right>
      <UNavigationMenu :items="items" variant="link" class="hidden lg:block" />

      <UColorModeButton />
    </template>

    <template #body>
      <UNavigationMenu :items="items" orientation="vertical" class="-mx-2.5" />
    </template>
  </UHeader>
</template>