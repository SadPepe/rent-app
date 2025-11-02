<template>
<div class="min-h-screen">
  <div class="flex flex-col items-center justify-center mt-20">
    <h1 class="text-3xl text-center text-primary">Вам на почту было отправленно письмо для подтверждения e-maill</h1>

    <p class="mt-5 text-base-content">
      Не получили письмо?
      <span
        @click="handleResend"
        class="underline hover:text-blue-900 cursor-pointer"
        > отправить еще раз</span
      >
    </p>
    <p v-if="success" class="mt-2 text-sm text-green-300">
      Your Verification Email is Sent
    </p>
  </div>
</div>
</template>

<script setup>
definePageMeta({
  layout: "verify-email",
  middleware: ["sanctum:auth"],
});

const config = useRuntimeConfig();
const client = useSanctumClient();

useHead({
  title: "verify email",
});

const success = ref(false);

const handleResend = async () => {
  await client(`/api/email/resend`, {
    method: "POST",
  });

  success.value = true;

  setTimeout(() => {
    success.value = false;
  }, 4000);
};
</script>

<style scoped></style>
