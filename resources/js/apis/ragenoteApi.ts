import { Api } from '@/apis/Api';
import handleError from '@/apis/handleApiError';

export default {
    // 🔹 Fetch all characters
    async getCharacters() {
        try {
            const response = await Api.get('/characters');
            return response.data;
        } catch (error) {
            throw handleError(error);
        }
    },

    // 🔹 Fetch single character
    async getCharacterById(id: number) {
        try {
            const response = await Api.get(`/characters/${id}`);
            return response.data;
        } catch (error) {
            throw handleError(error);
        }
    },

    // 🔹 Fetch videos for a character
    async getVideosByCharacterId(id: number) {
        try {
            const response = await Api.get(`/characters/${id}/videos`);
            return response.data;
        } catch (error) {
            throw handleError(error);
        }
    },

    // 🔹 Create video (multipart)
    async createVideo(formData: FormData) {
        try {
            console.log('============> Create video');
            // await Api.get('/sanctum/csrf-cookie');
            const response = await Api.post('/videos', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            return response.data;
        } catch (error) {
            throw handleError(error);
        }
    },

    async updateVideo(videoId: number, formData: FormData) {
        try {
            const response = await Api.post(`/videos/${videoId}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            return response.data;
        } catch (error) {
            throw handleError(error);
        }
    }
};
