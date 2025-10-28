<script setup lang="ts">
import MoveNotation from '@/components/notations/MoveNotation.vue';
import { dashboard } from '@/routes';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// This is your editable input field value
const input = ref('b+1,2 TND ffF+2');
const damage = ref<number>(0);
</script>

<template>
    <div class="mx-auto space-y-6 p-8">
        <div class="px-1 py-2">
            <Link :href="dashboard()">← Back to Dashboard</Link>
        </div>
        <h1 class="text-2xl font-bold">🎮 Notation Preview</h1>

        <!-- Text input so you can edit the notation live -->
        <input
            v-model="input"
            class="w-full rounded border px-4 py-2 text-lg"
            placeholder="Type notation like: ws+2 TND ffF+2"
        />
        <input
            v-model.number="damage"
            type="number"
            placeholder="Damage (optional)"
            class="w-full max-w-32 rounded border px-4 py-2 text-lg"
        />

        <!-- Render output -->
        <div class="rounded p-4">
            <MoveNotation :notation="input" :damage="damage" />
        </div>
    </div>
</template>
