<script setup lang="ts">
import { ParsedToken, useNotationParser } from '@/composables/useNotationParser';
import { computed, ref } from 'vue';

interface Props {
    notation: string;
}
const props = defineProps<Props>();

// UX Controls
const groupBg = ref('#333333');
type IconSizeType = 'tiny' | 'small' | 'medium' | 'large' | 'huge';
const iconSize = ref<IconSizeType>('medium');

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
        case 'tiny': return '16px';
        case 'small': return '24px';
        case 'medium': return '32px';
        case 'large': return '40px';
        case 'huge': return '52px';
        default: return '32px';
    }
});

// Excluded properties
const EXCLUDED_PROPS = ['TND', 'WSP', 'WBR', 'FBR', 'BBR', 'HB', 'HD', 'CH'];

// Determine group class
const getGroupClass = (group: ParsedToken[]) => {
    return group.every(t => t.type === 'property' && EXCLUDED_PROPS.includes(t.value))
        ? 'notation-group--excluded'
        : 'notation-group--normal';
};

const pixelMode = ref(false);

const renderingClass = computed(() =>
  pixelMode.value ? 'notation-render--pixelated' : ''
);
</script>

<template>
  <!-- Controls -->
  <div class="panel mb-4 flex items-center space-x-4">
    <label class="flex items-center space-x-2">
      <span class="text-sm font-medium">Group BG:</span>
      <input type="color" v-model="groupBg" class="h-8 w-10 cursor-pointer rounded border"/>
    </label>
    <label class="flex items-center space-x-2">
      <span class="text-sm font-medium">Icon size:</span>
      <select v-model="iconSize" class="cursor-pointer rounded border bg-white px-2 py-1 text-gray-800">
        <option v-for="size in ICON_SIZES" :key="size.value" :value="size.value">{{ size.label }}</option>
      </select>
    </label>
   <label>
  <input type="checkbox" v-model="pixelMode" />
  Pixel mode
</label>
  </div>

  <!-- Notation display -->
  <div class="notation-display mt-10 inline-flex flex-wrap items-center" :class="renderingClass">
    <template v-for="(group, gi) in groups" :key="props.notation + '-' + gi">
      <span
        :class="['mr-2 inline-flex items-center rounded-md px-2', getGroupClass(group)]"
        :style="getGroupClass(group) === 'notation-group--normal' ? { backgroundColor: groupBg, paddingTop: '1rem', paddingBottom: '1rem' } : {}"
      >
        <template v-for="(t, ti) in group" :key="props.notation + '-' + gi + '-' + ti + '-' + t.value">
          <template v-if="t.value === '+' || t.value === ','"></template>
          <img v-else-if="t.icon" :src="`/storage/notations/${t.icon}`" :alt="t.value" class="mx-1" :style="{ height: iconSizePx }"/>
          <span v-else class="mx-1 font-semibold">{{ t.value }}</span>
        </template>
      </span>

      <!-- next arrow -->
      <img v-if="gi < groups.length - 1" src="/storage/notations/next.png" class="mx-2 opacity-95" :style="{ height: `calc(${iconSizePx} / 1.5)` }" alt="→"/>
    </template>
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
    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    border-radius: 2px;
}

.notation-display {
    background-color: #111111;
    padding: 10px 15px;
}
.notation-render--pixelated {
  image-rendering: pixelated;
  image-rendering: crisp-edges; /* fallback */
}

.panel {
    background-color: #555555;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
}
</style>
