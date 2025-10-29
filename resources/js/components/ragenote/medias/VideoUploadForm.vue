<script setup lang="ts">
import ragenoteApi from '@/apis/ragenoteApi';
import InputError from '@/components/InputError.vue';
import { ref, watch } from 'vue';

interface Props {
    characterId?: number;
    video?: any;
    mode?: 'create' | 'edit';
}

const props = defineProps<Props>();
const emit = defineEmits<{
    (e: 'saved', video: any): void;
}>();

// Form fields
const title = ref(props.video?.title || '');
const youtubeUrl = ref(props.video?.youtube_url || '');
const notes = ref(props.video?.notes || '');
const notation = ref(props.video?.notation || '');
const file = ref<File | null>(null);

watch(
    () => props.video,
    (v) => {
        title.value = v?.title || '';
        youtubeUrl.value = v?.youtube_url || '';
        notes.value = v?.notes || '';
        notation.value = v?.notation || '';
        file.value = null;
    },
);

const errors = ref<Record<string, string>>({});
const processing = ref(false);
const uploadProgress = ref(0);

const CHUNK_SIZE = 5 * 1024 * 1024; // 5MB chunks

const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        file.value = target.files[0];
        uploadProgress.value = 0;
    }
};

// Chunked upload function
const uploadInChunks = async (file: File) => {
    const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
    let lastResponse = null;
    let uploadId: string | null = null; // ✅ persist upload session id

    for (let i = 0; i < totalChunks; i++) {
        const start = i * CHUNK_SIZE;
        const end = Math.min(start + CHUNK_SIZE, file.size);
        const chunk = file.slice(start, end);

        const formData = new FormData();
        formData.append('file', chunk);
        formData.append('chunkIndex', i.toString());
        formData.append('totalChunks', totalChunks.toString());
        formData.append('filename', file.name);
        if (uploadId) formData.append('uploadId', uploadId);

        const res = await ragenoteApi.uploadVideoChunk(formData, (event) => {
            const total = event.total ?? 1;
            uploadProgress.value = Math.floor(
                ((i + event.loaded / total) / totalChunks) * 100,
            );
        });

        // ✅ Keep using same uploadId for all next chunks
        if (res.uploadId && !uploadId) {
            uploadId = res.uploadId;
        }

        lastResponse = res;
    }

    return lastResponse; // should include filename and video_path
};

const submit = async () => {
    processing.value = true;
    errors.value = {};

    try {
        let finalFileName = props.video?.video_file || null;

        // If a new file is selected, upload in chunks
        if (file.value) {
            const res = await uploadInChunks(file.value);
            finalFileName = res.video_path; // store relative path, not just the name
        }

        // Prepare metadata
        const formData = new FormData();
        formData.append('title', title.value);
        formData.append('notes', notes.value);
        formData.append('notation', notation.value);
        if (youtubeUrl.value) formData.append('youtube_url', youtubeUrl.value);
        if (finalFileName) formData.append('video_file', finalFileName);
        if (props.mode !== 'edit')
            formData.append('character_id', props.characterId!.toString());
        if (props.mode === 'edit') formData.append('_method', 'PUT');

        // Save video metadata
        let video;
        if (props.mode === 'edit' && props.video) {
            video = await ragenoteApi.updateVideo(props.video.id, formData);
        } else {
            video = await ragenoteApi.createVideo(formData);
        }

        emit('saved', video);

        // Reset form for create
        if (props.mode !== 'edit') {
            title.value = '';
            youtubeUrl.value = '';
            notes.value = '';
            notation.value = '';
            file.value = null;
            uploadProgress.value = 0;
        }
    } catch (err: any) {
        if (err.response?.data?.errors) errors.value = err.response.data.errors;
        console.error(err);
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label>Title</label>
            <input v-model="title" class="w-full rounded border px-2 py-1" />
            <p v-if="errors.title" class="text-sm text-red-500">
                {{ errors.title }}
            </p>
        </div>

        <div>
            <label>Notes</label>
            <textarea
                v-model="notes"
                rows="4"
                placeholder="Add tournament tips or extra info..."
                class="w-full resize-y rounded border px-4 py-2 text-lg"
            ></textarea>
            <p v-if="errors.notes" class="text-sm text-red-500">
                {{ errors.notes }}
            </p>
        </div>

        <div class="grid gap-2">
            <label for="notation">Notation</label>
            <input
                id="notation"
                v-model="notation"
                placeholder="Enter combo notation (optional)"
                class="w-full rounded border px-4 py-2 text-lg"
            />
            <InputError :message="errors.notation" />
        </div>

        <div>
            <label>YouTube URL</label>
            <input
                v-model="youtubeUrl"
                class="w-full rounded border px-2 py-1"
            />
            <p v-if="errors.youtube_url" class="text-sm text-red-500">
                {{ errors.youtube_url }}
            </p>
        </div>

        <div>
            <label>Video File</label>
            <input
                type="file"
                @change="onFileChange"
                class="w-full rounded border px-2 py-1"
            />
            <p class="text-sm text-gray-500">
                Optional, replaces existing video if selected
            </p>
            <div v-if="uploadProgress > 0" class="mt-1">
                <progress :value="uploadProgress" max="100" class="w-full">
                    {{ uploadProgress }}%
                </progress>
            </div>
        </div>

        <button
            type="submit"
            class="rounded bg-blue-600 px-4 py-2 text-white"
            :disabled="processing"
        >
            {{ props.mode === 'edit' ? 'Update Video' : 'Upload Video' }}
        </button>
    </form>
</template>
