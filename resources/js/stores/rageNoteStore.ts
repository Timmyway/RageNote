import ragenoteApi from '@/apis/ragenoteApi';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useRageNoteStore = defineStore('rageNote', () => {
    // State
    const characters = ref<any[]>([]);
    const selectedCharacter = ref<any | null>(null);
    const videos = ref<any[]>([]);
    const loading = ref(false);

    const videosMeta = ref({
        current_page: 1,
        last_page: 1,
        per_page: 20,
        total: 0,
    });

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

    async function fetchVideos(id: number, page = 1) {
        loading.value = true;
        try {
            const res = await ragenoteApi.getVideosByCharacterId(id, page);

            videos.value = res.data; // paginated data
            videosMeta.value = {
                current_page: res.current_page,
                last_page: res.last_page,
                per_page: res.per_page,
                total: res.total,
            };
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
        videosMeta,
        fetchCharacters,
        selectCharacter,
        fetchCharacter,
        fetchVideos,
    };
});
