<script setup lang="ts">
import MoveNotation from '@/components/notations/MoveNotation.vue';
import { computed, ref } from 'vue';

interface Video {
    id: number;
    title: string;
    video_path?: string;
    video_url?: string;
    youtube_url?: string;
    thumbnail?: string;
    notation?: string;
    notes?: string;
    tags?: string[];
}

const props = defineProps<{ video: Video }>();

// Show video only on click
const isPlaying = ref(false);

const embedUrl = computed(() => {
    const url = props.video.youtube_url;
    if (!url) return null;

    let videoId: string | null = null;

    try {
        const parsedUrl = new URL(url);
        if (parsedUrl.hostname.includes('youtu.be')) {
            // short format: https://youtu.be/VIDEO_ID
            videoId = parsedUrl.pathname.slice(1);
        } else if (parsedUrl.searchParams.has('v')) {
            // long format: https://www.youtube.com/watch?v=VIDEO_ID
            videoId = parsedUrl.searchParams.get('v');
        }
    } catch {
        // fallback if URL parsing fails
        return null;
    }

    return videoId ? `https://www.youtube.com/embed/${videoId}` : null;
});

function playVideo() {
    isPlaying.value = true;
}
</script>

<template>
    <div
        class="video-card transform overflow-hidden rounded-lg border shadow-sm transition duration-200 hover:scale-105"
    >
        <h3 class="p-2 font-semibold">{{ video.id }} | {{ video.title }}</h3>

        <div class="flex h-full flex-col">
            <div
                class="relative w-full cursor-pointer"
                @click="playVideo"
                v-if="!isPlaying"
            >
                <img
                    :src="video.thumbnail || '/images/video-placeholder.WebP'"
                    alt="thumbnail"
                    class="aspect-video w-full object-cover"
                />
                <div
                    class="absolute inset-0 flex items-center justify-center bg-black/40"
                >
                    <span class="text-3xl text-white">▶</span>
                </div>
            </div>

            <div v-else class="aspect-video w-full">
                <video
                    v-if="video.video_url"
                    :src="video.video_url"
                    controls
                    autoplay
                    class="h-full w-full object-cover"
                />
                <iframe
                    v-else-if="embedUrl"
                    :src="embedUrl"
                    frameborder="0"
                    allowfullscreen
                    class="h-full w-full"
                ></iframe>
                <p v-else class="p-2 text-gray-500">
                    No video source available
                </p>
            </div>

            <div v-if="video.notation">
                <MoveNotation
                    :notation="video.notation ?? ''"
                    :is-readonly="true"
                    :default-icon-size="'small'"
                />
            </div>
            <div class="border-2 border-y-0 border-gray-200">
                <p class="px-3 py-2 text-sm">Notation: {{ video.notation }}</p>
            </div>
            <div class="flex-1 border-2 border-gray-200 bg-blue-600">
                <p class="max-h-32 overflow-auto px-3 py-2 text-sm">
                    {{ video.notes }}
                </p>
            </div>

            <div v-if="video.tags?.length" class="flex flex-wrap gap-1 p-2">
                <span
                    v-for="tag in video.tags"
                    :key="tag"
                    class="rounded bg-gray-200 px-2 py-1 text-xs text-gray-800"
                >
                    {{ tag }}
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.aspect-video {
    aspect-ratio: 16 / 9;
}
</style>
