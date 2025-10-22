<template>
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
    <CharacterCard
      v-for="character in characters"
      :key="character.id"
      :character="character"
      @select="onSelectCharacter"
    />
  </div>
</template>

<script setup lang="ts">
import CharacterCard from './CharacterCard.vue';

defineProps<{ characters: any[] }>();

const emit = defineEmits<{
  (e: 'selectCharacter', character: any): void;
}>();

function onSelectCharacter(character: any) {
  emit('selectCharacter', character);
}
</script>
