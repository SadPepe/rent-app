<script setup lang="ts">
const nuxtApp = useNuxtApp()
const { activeHeadings, updateHeadings } = useScrollspy()
const auth = useSanctumAuth()
const { logout } = useSanctumAuth()
const isAuthenticated = computed(() => !!auth.user.value)

const items = computed(() => {
  const baseItems = [
    {
      label: 'Features',
      to: '#features',
      active: activeHeadings.value.includes('features') && !activeHeadings.value.includes('pricing')
    },
    {
      label: 'Pricing',
      to: '#pricing',
      active: activeHeadings.value.includes('pricing')
    },
    {
      label: 'Testimonials',
      to: '#testimonials',
      active: activeHeadings.value.includes('testimonials') && !activeHeadings.value.includes('pricing')
    }
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
      <UNavigationMenu
        :items="items"
        variant="link"
        class="hidden lg:block"
      />

      <UButton
        label="Download App"
        variant="subtle"
        class="hidden lg:block"
      />

      <UColorModeButton />
    </template>

    <template #body>
      <UNavigationMenu
        :items="items"
        orientation="vertical"
        class="-mx-2.5"
      />
      <UButton
        class="mt-4"
        label="Download App"
        variant="subtle"
        block
      />
    </template>
  </UHeader>
</template>