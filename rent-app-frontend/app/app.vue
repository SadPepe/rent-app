<script setup>
useHead({
  bodyAttrs: {
    class: 'bg-base-100'
  }
})
</script>
<template>
<Html data-theme="coffee">
  <navbar />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
  </Html>
</template>
