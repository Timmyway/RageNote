<script setup lang="ts">
import {
    ParsedToken,
    useNotationParser,
} from '@/composables/useNotationParser';
import NotationGuide from '@/pages/notations/NotationGuide.vue';
import html2canvas from 'html2canvas-pro';
import { computed, ref } from 'vue';

type IconSizeType = 'tiny' | 'small' | 'medium' | 'large' | 'huge';
interface Props {
    notation: string;
    damage?: number;
    isReadonly?: boolean;
    defaultIconSize?: IconSizeType;
}
const props = withDefaults(defineProps<Props>(), {
    isReadonly: false,
    defaultIconSize: 'medium',
});

const exportRef = ref<HTMLElement | null>(null);

// UX Controls
const groupBg = ref('#333333');
const groupTextColor = ref('#FFFFFF');
const panelBg = ref('#222222');
const iconSize = ref<IconSizeType>(props.defaultIconSize!);

const ICON_SIZES: { label: string; value: IconSizeType }[] = [
    { label: 'Tiny', value: 'tiny' },
    { label: 'Small', value: 'small' },
    { label: 'Medium', value: 'medium' },
    { label: 'Large', value: 'large' },
    { label: 'Huge', value: 'huge' },
];

// Parse notation
const { tokens } = useNotationParser(computed(() => props.notation));

// Split tokens into groups
const groups = computed(() => {
    const result: ParsedToken[][] = [];
    let current: ParsedToken[] = [];
    for (const t of tokens.value) {
        if (t.type === 'separator') {
            if (current.length) result.push(current);
            current = [];
        } else {
            current.push(t);
        }
    }
    if (current.length) result.push(current);
    return result;
});

// Icon size mapping
const iconSizePx = computed(() => {
    switch (iconSize.value) {
        case 'tiny':
            return '16px';
        case 'small':
            return '24px';
        case 'medium':
            return '32px';
        case 'large':
            return '40px';
        case 'huge':
            return '52px';
        default:
            return '32px';
    }
});

const isConfined = computed(
    () => iconSize.value === 'tiny' || iconSize.value === 'small',
);

// Excluded properties
const EXCLUDED_PROPS = ['TND', 'WSP', 'WBR', 'FBR', 'BBR', 'HB', 'HD', 'CH'];

// Determine group class
const getGroupClass = (group: ParsedToken[]) => {
    return group.every(
        (t) => t.type === 'property' && EXCLUDED_PROPS.includes(t.value),
    )
        ? 'notation-group--excluded'
        : 'notation-group--normal';
};

const pixelMode = ref(false);

const renderingClass = computed(() =>
    pixelMode.value ? 'notation-render--pixelated' : '',
);

const exportAsImage = async () => {
    if (!exportRef.value) return;

    const el = exportRef.value;
    const prevBoxShadow = el.style.boxShadow;

    el.style.boxShadow = 'none';

    const canvas = await html2canvas(el, {
        scale: 2,
        useCORS: true,
        logging: false,
    });

    el.style.boxShadow = prevBoxShadow;

    const link = document.createElement('a');
    link.download = `notation-${Date.now()}.png`;
    link.href = canvas.toDataURL('image/png');
    link.click();
};
</script>

<template>
    <!-- Controls -->
    <div
        v-if="!isReadonly"
        class="panel mb-4 flex items-center space-x-4"
        :class="{ 'panel--confined': isConfined }"
    >
        <label class="flex items-center space-x-2">
            <span class="text-sm font-medium">Panel BG:</span>
            <input
                type="color"
                v-model="panelBg"
                class="h-8 w-10 cursor-pointer rounded border"
            />
        </label>
        <label class="flex items-center space-x-2">
            <span class="text-sm font-medium">Group BG:</span>
            <input
                type="color"
                v-model="groupBg"
                class="h-8 w-10 cursor-pointer rounded border"
            />
        </label>
        <label class="flex items-center space-x-2">
            <span class="text-sm font-medium">Group text:</span>
            <input
                type="color"
                v-model="groupTextColor"
                class="h-8 w-10 cursor-pointer rounded border"
            />
        </label>
        <label class="flex items-center space-x-2">
            <span class="text-sm font-medium">Icon size:</span>
            <select
                v-model="iconSize"
                class="cursor-pointer rounded border bg-white px-2 py-1 text-gray-800"
            >
                <option
                    v-for="size in ICON_SIZES"
                    :key="size.value"
                    :value="size.value"
                >
                    {{ size.label }}
                </option>
            </select>
        </label>
        <label>
            <input type="checkbox" v-model="pixelMode" />
            Pixel mode
        </label>
        <button
            v-if="!isReadonly"
            @click="exportAsImage"
            class="rounded bg-black px-3 py-1 text-sm text-white hover:opacity-90"
        >
            Export image
        </button>

        <div class="ml-auto">
            <NotationGuide />
        </div>
    </div>

    <!-- Notation display -->
    <div
        ref="exportRef"
        class="notation-display inline-flex flex-wrap items-center gap-2"
        :class="[renderingClass, isReadonly ? 'mt-1' : 'mt-10']"
        :style="{ backgroundColor: panelBg }"
    >
        <template
            v-for="(group, gi) in groups"
            :key="props.notation + '-' + gi"
        >
            <span
                :class="[
                    'mr-2 inline-flex items-center rounded-md px-2',
                    getGroupClass(group),
                ]"
                :style="
                    getGroupClass(group) === 'notation-group--normal'
                        ? {
                              backgroundColor: groupBg,
                              color: groupTextColor,
                              paddingTop: '1rem',
                              paddingBottom: '1rem',
                          }
                        : {}
                "
            >
                <template
                    v-for="(t, ti) in group"
                    :key="props.notation + '-' + gi + '-' + ti + '-' + t.value"
                >
                    <template
                        v-if="t.value === '+' || t.value === ','"
                    ></template>
                    <img
                        v-else-if="t.icon"
                        :src="`/storage/notations/${t.icon}`"
                        :alt="t.value"
                        class="mx-1"
                        :style="{ height: iconSizePx }"
                    />
                    <span v-else class="mx-1 font-semibold">{{ t.value }}</span>
                </template>
            </span>

            <!-- next arrow -->
            <img
                v-if="gi < groups.length - 1"
                src="/storage/notations/next.png"
                class="mx-2 opacity-95"
                :style="{ height: `calc(${iconSizePx} / 1.5)` }"
                alt="→"
            />
        </template>

        <div
            v-if="damage"
            data-html2canvas-ignore
            class="damage-display flex items-center justify-end bg-gradient-to-r from-yellow-400 via-orange-400 to-yellow-300 bg-clip-text text-center text-xl font-extrabold tracking-wide text-transparent drop-shadow-[0_0_6px_rgba(255,200,0,0.8)]"
        >
            {{ damage }} <br />DMG
        </div>
    </div>
</template>

<style scoped>
.notation-group--normal {
    background-color: #333333;
}

.notation-group--excluded {
    background-color: transparent !important;
}
.notation-group--excluded img {
    box-shadow:
        rgba(0, 0, 0, 0.4) 0px 2px 4px,
        rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
        rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    border-radius: 2px;
}

.notation-display {
    padding: 10px 15px;
    box-shadow:
        rgba(0, 0, 0, 0.05) 0px 0px 0px 1px,
        rgb(209, 213, 219) 0px 0px 0px 1px inset;
    border-radius: 2px;
    max-height: 150px;
    overflow-y: auto;
}
.notation-render--pixelated {
    image-rendering: pixelated;
    image-rendering: crisp-edges; /* fallback */
}

.panel {
    background-color: #555555;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow:
        rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em,
        rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em,
        rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
}

.panel--small {
    padding: 0.5rem;
    gap: 0.5rem;
    background-color: red !important;
}

.damage-display {
    font-family: 'Audiowide', sans-serif;
}
</style>
