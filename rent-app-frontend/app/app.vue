<script setup>
useHead({
  bodyAttrs: {
    class: 'bg-base-200'
  }
})
</script>
<template>
<Html>
      <navbar />
      <NuxtLayout>
        <NuxtPage />
      </NuxtLayout>
      <Footer />
</Html>
</template>
