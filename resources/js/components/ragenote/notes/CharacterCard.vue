<script setup lang="ts">
defineProps<{ character: any }>();
</script>

<template>
  <div @click="$emit('select', character)"
       class="cursor-pointer rounded-lg bg-gray-800 p-3 hover:bg-gray-700 transition">
    <img :src="character.image" alt="" class="w-20 h-auto rounded-full mx-auto"/>
    <p class="text-center mt-2 font-semibold">{{ character.name }}</p>
  </div>
</template>
