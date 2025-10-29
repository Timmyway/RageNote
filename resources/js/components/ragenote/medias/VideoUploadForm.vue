<script setup lang="ts">
import ragenoteApi from '@/apis/ragenoteApi';
import { ref } from 'vue';

const props = defineProps({
    characterId: {
        type: Number,
        required: true,
    },
});

const title = ref('');
const youtubeUrl = ref('');
const notes = ref('');
const videoFile = ref<File | null>(null);
const isSubmitting = ref(false);
const error = ref<string | null>(null);
const success = ref<string | null>(null);

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files?.length) {
        videoFile.value = target.files[0];
    }
};

const handleSubmit = async () => {
    error.value = null;
    success.value = null;

    if (!videoFile.value && !youtubeUrl.value) {
        error.value = 'Please provide either a video file or a YouTube URL.';
        return;
    }

    const formData = new FormData();
    formData.append('character_id', props.characterId.toString());
    formData.append('title', title.value);
    if (notes.value) formData.append('notes', notes.value);
    if (youtubeUrl.value) formData.append('youtube_url', youtubeUrl.value);
    if (videoFile.value) formData.append('video_file', videoFile.value);

    isSubmitting.value = true;

    try {
        const data = await ragenoteApi.createVideo(formData);
        success.value = `✅ Video "${data.title}" uploaded successfully!`;
        title.value = '';
        youtubeUrl.value = '';
        notes.value = '';
        videoFile.value = null;
    } catch (err: any) {
        error.value = err.message || 'Upload failed.';
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <div
        class="w-full max-w-lg rounded-2xl bg-gray-900 p-6 text-white shadow-lg"
    >
        <h2 class="mb-4 text-xl font-semibold">Upload Video</h2>

        <div class="space-y-3">
            <input
                v-model="title"
                type="text"
                placeholder="Video title"
                class="w-full rounded border border-gray-700 bg-gray-800 p-2"
            />

            <textarea
                v-model="notes"
                placeholder="Notes (optional)"
                class="w-full rounded border border-gray-700 bg-gray-800 p-2"
            ></textarea>

            <input
                type="file"
                accept="video/mp4,video/webm,video/quicktime"
                @change="handleFileChange"
                class="w-full text-sm text-gray-400"
            />

            <input
                v-model="youtubeUrl"
                type="text"
                placeholder="YouTube link (optional)"
                class="w-full rounded border border-gray-700 bg-gray-800 p-2"
            />

            <button
                @click="handleSubmit"
                :disabled="isSubmitting"
                class="w-full rounded bg-blue-600 px-4 py-2 font-semibold transition hover:bg-blue-700"
            >
                {{ isSubmitting ? 'Uploading...' : 'Upload' }}
            </button>

            <p v-if="error" class="text-sm text-red-400">{{ error }}</p>
            <p v-if="success" class="text-sm text-green-400">{{ success }}</p>
        </div>
    </div>
</template>
