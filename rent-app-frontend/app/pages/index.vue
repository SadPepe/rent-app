<template>
    <div>
        this is index
  
    </div>
</template>

<script setup>

definePageMeta({
    middleware: ['sanctum:auth',
        'verified'
    ]
})

const {isAuthenticated, user} = useSanctumAuth()

</script>