<script setup lang="ts">
import VideoUploadForm from '@/components/ragenote/medias/VideoUploadForm.vue';
import VideoPlayer from '@/components/ragenote/notes/VideoPlayer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { useRageNoteStore } from '@/stores/rageNoteStore';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';

import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

import { Button } from '@/components/ui/button';

const store = useRageNoteStore();
const { character } = usePage().props as any;
const id = character.id;

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Home', href: dashboard().url },
    { title: 'Character', href: `/characters/${id}` },
];

// Initialize store
store.selectedCharacter = character;
store.fetchVideos(id);

const onVideoSaved = (video: any, isEdit = false) => {
    if (isEdit) {
        const idx = store.videos.findIndex((v) => v.id === video.id);
        if (idx !== -1) store.videos[idx] = video;
    } else {
        store.videos.push(video);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="store.selectedCharacter?.name || 'Character'" />

        <div class="p-6">
            <!-- Character Header -->
            <div class="mb-6 flex items-center gap-4">
                <img
                    :src="store.selectedCharacter?.image"
                    alt=""
                    class="h-24 w-24 rounded-lg object-cover"
                />
                <div>
                    <h1 class="text-2xl font-bold">
                        {{ store.selectedCharacter?.name }}
                    </h1>
                    <p class="text-sm text-gray-400">
                        Character overview & videos
                    </p>
                </div>

                <div class="ml-auto">
                    <!-- Add Video Button + Dialog -->
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="default">➕ Add Video</Button>
                        </DialogTrigger>
                        <DialogContent class="max-w-lg">
                            <DialogHeader>
                                <DialogTitle>Add New Video</DialogTitle>
                                <DialogDescription>
                                    Upload a local video or paste a YouTube link
                                    for
                                    {{ store.selectedCharacter?.name }}.
                                </DialogDescription>
                            </DialogHeader>

                            <VideoUploadForm
                                :character-id="id"
                                mode="create"
                                @saved="(video) => onVideoSaved(video, false)"
                            />

                            <DialogFooter>
                                <DialogClose as-child>
                                    <Button variant="secondary">Close</Button>
                                </DialogClose>
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>

            <!-- Videos -->
            <div v-if="store.videos.length">
                <h2 class="mb-2 text-xl font-semibold">Videos</h2>
                <div
                    class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                >
                    <div
                        v-for="video in store.videos"
                        :key="video.id"
                        class="relative"
                    >
                        <VideoPlayer :video="video" />

                        <!-- Edit Button -->
                        <div class="absolute top-2 right-2">
                            <Dialog>
                                <DialogTrigger as-child>
                                    <Button size="sm" variant="outline"
                                        >✏️</Button
                                    >
                                </DialogTrigger>

                                <DialogContent class="max-w-lg">
                                    <DialogHeader>
                                        <DialogTitle>Edit Video</DialogTitle>
                                        <DialogDescription>
                                            Update the video for
                                            {{ store.selectedCharacter?.name }}.
                                        </DialogDescription>
                                    </DialogHeader>

                                    <VideoUploadForm
                                        :video="video"
                                        mode="edit"
                                        @saved="
                                            (updatedVideo) =>
                                                onVideoSaved(updatedVideo, true)
                                        "
                                    />

                                    <DialogFooter>
                                        <DialogClose as-child>
                                            <Button variant="secondary"
                                                >Close</Button
                                            >
                                        </DialogClose>
                                    </DialogFooter>
                                </DialogContent>
                            </Dialog>
                        </div>
                    </div>
                </div>
            </div>

            <p v-else class="mt-4 text-gray-400">
                No videos yet for this character.
            </p>
        </div>
    </AppLayout>
</template>
