<script setup lang="ts">
import CharacterList from '@/components/ragenote/notes/CharacterList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { useRageNoteStore } from '@/stores/rageNoteStore';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: dashboard().url,
    },
];

const rageNoteStore = useRageNoteStore();

rageNoteStore.fetchCharacters();

function onSelectCharacter(character: any) {
    rageNoteStore.selectCharacter(character);
    router.get(`/characters/${character.id}`)
    // rageNoteStore.fetchVideos(character.id);
}
</script>

<template>
    <Head title="Characters" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1>Dashboard</h1>
        </div>

        <!-- CharacterList usage -->
        <CharacterList
            :characters="rageNoteStore.characters"
            @selectCharacter="onSelectCharacter"
        />
    </AppLayout>
</template>
