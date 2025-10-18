<template>
    <form @submit.prevent="handleLogin" class="flex justify-center">
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <legend class="fieldset-legend">Login</legend>

            <label class="label">Email</label>
            <input v-model="formData.email" type="email" class="input" placeholder="Email" />

            <label class="label">Password</label>
            <input v-model="formData.password" type="password" class="input" placeholder="Password" />

            <button type="submit" class="btn btn-primary mt-4">Login</button>
        </fieldset>
    </form>
</template>

<script setup>
definePageMeta({ layout: 'auth', middleware: 'sanctum:guest' });

const { login } = useSanctumAuth()

const formData = ref({
    email: '',
    password: '',
})

const handleLogin = async () => {
    await login(formData.value)
}
</script>
