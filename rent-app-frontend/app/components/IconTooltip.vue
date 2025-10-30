<!-- components/IconTooltip.vue -->
<template>
  <div
    v-if="active"
    class="tooltip tooltip-bottom"
    :data-tip="label"
  >
    <Icon :name="icon" class="w-5 h-5 text-primary" />
  </div>
</template>

<script setup lang="ts">
defineProps<{
  icon: string
  label: string
  active: boolean
}>()
</script>