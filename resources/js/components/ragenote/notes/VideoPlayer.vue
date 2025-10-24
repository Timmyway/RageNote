<script setup lang="ts">
import { computed, ref } from 'vue';

interface Video {
    id: number;
    title: string;
    video_path?: string;
    video_url?: string;
    youtube_url?: string;
    thumbnail?: string;
    tags?: string[];
}

const props = defineProps<{ video: Video }>();

// Show video only on click
const isPlaying = ref(false);

const embedUrl = computed(() => {
    if (!props.video.youtube_url) return null;
    const urlParts = props.video.youtube_url.split('v=');
    const videoId = urlParts.length > 1 ? urlParts[1] : props.video.youtube_url;
    return `https://www.youtube.com/embed/${videoId}`;
});

function playVideo() {
    isPlaying.value = true;
}
</script>

<template>
    <div class="video-card overflow-hidden rounded-lg border shadow-sm transform transition hover:scale-105 duration-200">
        <h3 class="p-2 font-semibold">{{ video.title }}</h3>

        <div
            class="relative w-full cursor-pointer"
            @click="playVideo"
            v-if="!isPlaying"
        >
            <img
                :src="video.thumbnail || '/images/video-placeholder.jpg'"
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
            <p v-else class="p-2 text-gray-500">No video source available</p>
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
</template>

<style scoped>
.aspect-video {
    aspect-ratio: 16 / 9;
}
</style>
