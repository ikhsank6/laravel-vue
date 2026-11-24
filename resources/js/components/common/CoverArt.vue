<script setup lang="ts">
import { computed } from 'vue';
import { coverTone } from '@/lib/coverTone';
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        title: string;
        size?: 'sm' | 'md' | 'lg' | 'fill';
        class?: string;
    }>(),
    { size: 'md' },
);

// Content without an uploaded image gets a typographic cover instead of a stock photo.
const background = computed(() => coverTone(props.title));

const sizeClass = computed(
    () =>
        ({
            sm: 'size-10 rounded-xs p-1 text-[9px]',
            md: 'size-14 rounded-sm p-1.5 text-[10px]',
            lg: 'aspect-[4/3] w-full rounded-md p-5 text-xl sm:text-2xl',
            fill: 'h-full w-full rounded-md p-6 text-2xl sm:text-4xl',
        })[props.size],
);

const clamp = computed(() =>
    props.size === 'sm' || props.size === 'md'
        ? 'line-clamp-3'
        : 'line-clamp-4',
);
</script>

<template>
    <div
        :class="
            cn(
                'flex shrink-0 items-end overflow-hidden leading-tight font-bold text-white',
                sizeClass,
                props.class,
            )
        "
        :style="{ backgroundColor: background }"
        aria-hidden="true"
    >
        <span :class="clamp">{{ title }}</span>
    </div>
</template>
