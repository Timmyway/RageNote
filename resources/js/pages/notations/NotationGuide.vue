<script setup lang="ts">
import { ref } from 'vue';

const isOpen = ref(false);

const DIRECTIONS = [
    { key: 'u', label: 'Up' },
    { key: 'd', label: 'Down' },
    { key: 'f', label: 'Forward' },
    { key: 'b', label: 'Back' },
    { key: 'uf', label: 'Up-Forward' },
    { key: 'df', label: 'Down-Forward' },
    { key: 'ub', label: 'Up-Back' },
    { key: 'db', label: 'Down-Back' },
    { key: 'Uppercase', label: 'Hold (e.g., DF = Down-Forward hold)' },
];

const BUTTONS = [
    { key: '1', label: 'Attack 1' },
    { key: '2', label: 'Attack 2' },
    { key: '3', label: 'Attack 3' },
    { key: '4', label: 'Attack 4' },
    { key: '+', label: 'Simultaneous input (e.g., 1+2)' },
    { key: ',', label: 'Next input after short pause' },
];

const SPECIAL = [
    { key: 'ws', label: 'While Standing' },
    { key: 'wr', label: 'While Running' },
    { key: 'qcf', label: 'Quarter Circle Forward' },
    { key: 'qcb', label: 'Quarter Circle Back' },
    { key: 'hcf', label: 'Half Circle Forward' },
    { key: 'hcb', label: 'Half Circle Back' },
    { key: 'dp', label: 'Dragon Punch motion' },
];

const PROPERTIES = [
    { key: 'ra', label: 'Rage Art' },
    { key: 'TND', label: 'Tornado' },
    { key: 'WSP', label: 'Wall Splat' },
    { key: 'WBR', label: 'Wall Break' },
    { key: 'FBR', label: 'Floor Break' },
    { key: 'BBR', label: 'Balcony Break' },
    { key: 'HB', label: 'Heat Burst' },
    { key: 'HD', label: 'Heat Dash' },
    { key: 'CH', label: 'Counter Hit' },
    { key: 'STB', label: 'Stun Break' },
    { key: 'DVK', label: 'Devil Kazuya' },
];

function toggleGuide() {
    isOpen.value = !isOpen.value;
}
</script>

<template>
    <div class="relative inline-block">
        <button
            @click="toggleGuide"
            class="rounded bg-blue-600 px-2 py-1 text-sm font-medium text-white hover:bg-blue-700"
        >
            ?
        </button>

        <div
            v-if="isOpen"
            class="absolute top-full right-0 z-50 mt-2 max-h-[640px] w-80 overflow-y-auto rounded border border-gray-700 bg-gray-800 p-4 text-base text-white shadow-lg"
        >
            <h4 class="mb-2 font-semibold">Notation Guide</h4>

            <div class="mb-2">
                <h5 class="mb-1 font-medium underline">Directions</h5>
                <ul class="list-inside list-disc">
                    <li v-for="d in DIRECTIONS" :key="d.key">
                        {{ d.key }} → {{ d.label }}
                    </li>
                </ul>
            </div>

            <div class="mb-2">
                <h5 class="mb-1 font-medium underline">Buttons</h5>
                <ul class="list-inside list-disc">
                    <li v-for="b in BUTTONS" :key="b.key">
                        {{ b.key }} → {{ b.label }}
                    </li>
                </ul>
            </div>

            <div class="mb-2">
                <h5 class="mb-1 font-medium underline">
                    Special Inputs / Motions
                </h5>
                <ul class="list-inside list-disc">
                    <li v-for="s in SPECIAL" :key="s.key">
                        {{ s.key }} → {{ s.label }}
                    </li>
                </ul>
            </div>

            <div>
                <h5 class="mb-1 font-medium underline">
                    Properties / Special Moves
                </h5>
                <ul class="list-inside list-disc">
                    <li v-for="p in PROPERTIES" :key="p.key">
                        {{ p.key }} → {{ p.label }}
                    </li>
                </ul>
            </div>

            <button
                @click="toggleGuide"
                class="mt-2 rounded bg-red-600 px-2 py-1 text-xs hover:bg-red-700"
            >
                Close
            </button>
        </div>
    </div>
</template>
