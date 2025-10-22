<template>
  <div>
    <h1>Guest Page</h1>
  </div>
</template>

<script setup>
definePageMeta({
    layout: 'auth',
    middleware: 'sanctum:guest',
});
</script>