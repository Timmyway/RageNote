<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <!-- Background -->
        <rect width="100" height="100" rx="15" ry="15" fill="#1f1f1f" />

        <!-- Letter R (rage) -->
        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="central"
                font-family="Arial, sans-serif" font-size="60" font-weight="bold" fill="#e63946">
            R
        </text>

        <!-- Small Note symbol (musical note style) -->
        <circle cx="70" cy="25" r="8" fill="#f1faee"/>
        <rect x="74" y="15" width="4" height="20" fill="#f1faee" />
    </svg>

</template>
