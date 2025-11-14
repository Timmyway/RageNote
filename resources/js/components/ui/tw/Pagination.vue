<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';

interface Props {
    currentPage: number;
    lastPage: number;
    maxButtons?: number; // e.g. show 5 page buttons around the current one
}

const props = withDefaults(defineProps<Props>(), {
    currentPage: 1,
    lastPage: 1,
    maxButtons: 5,
});

const emit = defineEmits<{
    (e: 'change', page: number): void;
}>();

const canGoPrev = computed(() => props.currentPage > 1);
const canGoNext = computed(() => props.currentPage < props.lastPage);

const goToPrev = () =>
    canGoPrev.value && emit('change', props.currentPage - 1);

const goToNext = () =>
    canGoNext.value && emit('change', props.currentPage + 1);

//
// --- Page Number Logic with Ellipsis ---
//
const pages = computed(() => {
    const pages: (number | '...')[] = [];
    const total = props.lastPage;
    const current = props.currentPage;
    const max = props.maxButtons; // visible page numbers around current

    if (total <= max + 2) {
        // All pages fit
        for (let i = 1; i <= total; i++) pages.push(i);
        return pages;
    }

    const half = Math.floor(max / 2);
    let start = current - half;
    let end = current + half;

    if (start <= 2) {
        start = 1;
        end = max;
    } else if (end >= total - 1) {
        start = total - max + 1;
        end = total;
    }

    // First page
    pages.push(1);
    if (start > 2) pages.push('...');

    // Buttons around current page
    for (let i = start; i <= end; i++) {
        if (i > 1 && i < total) pages.push(i);
    }

    // Last page
    if (end < total - 1) pages.push('...');
    if (total > 1) pages.push(total);

    return pages;
});

const selectPage = (page: number | '...') => {
    if (page !== '...') emit('change', page);
};
</script>

<template>
    <div class="flex items-center gap-2 mt-6">

        <!-- Prev -->
        <button
            class="px-3 py-1 text-sm border rounded-md hover:bg-gray-800 disabled:opacity-40"
            :disabled="!canGoPrev"
            @click="goToPrev"
        >
            Prev
        </button>

        <!-- Page numbers -->
        <template v-for="p in pages" :key="p">
            <button
                v-if="p !== '...'"
                class="px-3 py-1 text-sm border rounded-md hover:bg-gray-800"
                :class="{
                    'text-white border-yellow-400 font-semibold shadow':
                        p === currentPage,
                }"
                @click="selectPage(p)"
            >
                {{ p }}
            </button>

            <span v-else class="px-2 text-gray-400">…</span>
        </template>

        <!-- Next -->
        <button
            class="px-3 py-1 text-sm border rounded-md hover:bg-gray-800 disabled:opacity-40"
            :disabled="!canGoNext"
            @click="goToNext"
        >
            Next
        </button>
    </div>
</template>
