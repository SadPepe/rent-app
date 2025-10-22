<template>

        <slot />

</template>

<script setup>
const { logout } = useSanctumAuth()
const handleLogout = async () => {
  await logout()
}
</script>