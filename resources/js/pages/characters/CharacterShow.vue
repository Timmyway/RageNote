<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { useRageNoteStore } from '@/stores/rageNoteStore';
import VideoPlayer from '@/components/ragenote/notes/VideoPlayer.vue';

const store = useRageNoteStore();

// ✅ Get character from page props
const { character } = usePage().props as any;

// id comes from the character itself
const id = character.id;

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Home', href: dashboard().url },
  { title: 'Character', href: `/characters/${id}` },
];

// Initialize store
store.selectedCharacter = character;
store.fetchVideos(id);
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head :title="store.selectedCharacter?.name || 'Character'" />

    <div class="p-6">
      <div class="flex items-center gap-4 mb-6">
        <img
          :src="store.selectedCharacter?.image"
          alt=""
          class="w-24 h-24 rounded-lg object-cover"
        />
        <h1 class="text-2xl font-bold">{{ store.selectedCharacter?.name }}</h1>
      </div>

      <div v-if="store.videos.length">
        <h2 class="text-xl mb-2 font-semibold">Videos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <VideoPlayer
                v-for="video in store.videos"
                :key="video.id"
                :video="video"
            />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
