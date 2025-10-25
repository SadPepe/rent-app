<script setup>
useHead({
  bodyAttrs: {
    class: 'bg-base-100'
  }
})
</script>
<template>
<Html data-theme="silk">
  <navbar />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
  </Html>
</template>
