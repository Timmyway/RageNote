import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useRageNoteStore = defineStore('rageNote', () => {
  // State
  const characters = ref<any[]>([]);
  const selectedCharacter = ref<any | null>(null);
  const videos = ref<any[]>([]);
  const loading = ref(false);

  // Actions
  async function fetchCharacters() {
    loading.value = true;
    try {
      const res = await axios.get('/api/v1/characters');
      characters.value = res.data;
    } finally {
      loading.value = false;
    }
  }

  function selectCharacter(character: any) {
    selectedCharacter.value = character;
    videos.value = []; // reset videos when selecting new character
  }

  async function fetchVideos(characterId: number) {
    loading.value = true;
    try {
      const res = await axios.get(`/api/v1/characters/${characterId}/videos`);
      videos.value = res.data;
    } finally {
      loading.value = false;
    }
  }

  return {
    characters,
    selectedCharacter,
    videos,
    loading,
    fetchCharacters,
    selectCharacter,
    fetchVideos,
  };
});
