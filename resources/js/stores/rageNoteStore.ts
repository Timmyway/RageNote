import ragenoteApi from '@/apis/ragenoteApi';
import { defineStore } from 'pinia';
import { ref } from 'vue';

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
            characters.value = await ragenoteApi.getCharacters();
        } finally {
            loading.value = false;
        }
    }

    function selectCharacter(character: any) {
        selectedCharacter.value = character;
        videos.value = []; // reset videos when selecting new character
    }

    async function fetchVideos(id: number) {
        loading.value = true;
        try {
            videos.value = await ragenoteApi.getVideosByCharacterId(id);
        } finally {
            loading.value = false;
        }
    }

    async function fetchCharacter(id: number) {
        selectedCharacter.value = await ragenoteApi.getCharacterById(id);
    }

    return {
        characters,
        selectedCharacter,
        videos,
        loading,
        fetchCharacters,
        selectCharacter,
        fetchCharacter,
        fetchVideos,
    };
});
