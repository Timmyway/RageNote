<script setup lang="ts">
import ragenoteApi from '@/apis/ragenoteApi';
import { ref, watch } from 'vue';

interface Props {
    characterId?: number;
    video?: any; // existing video for edit
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

// Watch video prop for edits
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

const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        file.value = target.files[0];
    }
};

const submit = async () => {
    processing.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('notes', notes.value);
    formData.append('notation', notation.value);
    if (youtubeUrl.value) formData.append('youtube_url', youtubeUrl.value);
    if (file.value) formData.append('video_file', file.value);

    try {
        let video;
        if (props.mode === 'edit' && props.video) {
            formData.append('_method', 'PUT'); // Laravel needs this
            video = await ragenoteApi.updateVideo(props.video.id, formData);
        } else {
            formData.append('character_id', props.characterId!.toString());
            video = await ragenoteApi.createVideo(formData);
        }

        emit('saved', video);

        // Reset form only for create mode
        if (props.mode !== 'edit') {
            title.value = '';
            youtubeUrl.value = '';
            notes.value = '';
            file.value = null;
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
                id="notes"
                name="notes"
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
            <Label for="notation">Notation</Label>
            <input
                id="notation"
                name="notation"
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
