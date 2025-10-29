<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { dashboard } from '@/routes'
import { type BreadcrumbItem } from '@/types'
import { useRageNoteStore } from '@/stores/rageNoteStore'
import VideoPlayer from '@/components/ragenote/notes/VideoPlayer.vue'
import VideoUploadForm from '@/components/ragenote/medias/VideoUploadForm.vue'

import {
  Dialog,
  DialogTrigger,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
  DialogClose,
} from '@/components/ui/dialog'

import { Button } from '@/components/ui/button'

const store = useRageNoteStore()
const { character } = usePage().props as any

const id = character.id

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Home', href: dashboard().url },
  { title: 'Character', href: `/characters/${id}` },
]

store.selectedCharacter = character
store.fetchVideos(id)
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head :title="store.selectedCharacter?.name || 'Character'" />

    <div class="p-6">
      <!-- Character Header -->
      <div class="flex items-center gap-4 mb-6">
        <img
          :src="store.selectedCharacter?.image"
          alt=""
          class="w-24 h-24 rounded-lg object-cover"
        />
        <div>
          <h1 class="text-2xl font-bold">
            {{ store.selectedCharacter?.name }}
          </h1>
          <p class="text-sm text-gray-400">Character overview & videos</p>
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
                  Upload a local video or paste a YouTube link for
                  {{ store.selectedCharacter?.name }}.
                </DialogDescription>
              </DialogHeader>

              <VideoUploadForm :character-id="id" />

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
        <h2 class="text-xl mb-2 font-semibold">Videos</h2>
        <div
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
        >
          <VideoPlayer
            v-for="video in store.videos"
            :key="video.id"
            :video="video"
          />
        </div>
      </div>

      <p v-else class="text-gray-400 mt-4">No videos yet for this character.</p>
    </div>
  </AppLayout>
</template>
