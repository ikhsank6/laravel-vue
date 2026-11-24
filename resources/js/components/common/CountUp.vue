<script setup lang="ts">
import { usePreferredReducedMotion } from '@vueuse/core';
import { onMounted, ref } from 'vue';

// Counts up once when the page opens so a changed number is noticed (admin MOTION 2).
// The final value is always in the DOM for screen readers and reduced-motion users.
const props = defineProps<{ value: number }>();

const reduced = usePreferredReducedMotion();
const shown = ref(props.value);

onMounted(() => {
    if (reduced.value === 'reduce' || props.value === 0) {
        return;
    }

    shown.value = 0;
    const start = performance.now();
    const duration = 700;

    const tick = (now: number) => {
        const progress = Math.min((now - start) / duration, 1);
        shown.value = Math.round((1 - Math.pow(1 - progress, 3)) * props.value);

        if (progress < 1) {
            requestAnimationFrame(tick);
        }
    };

    requestAnimationFrame(tick);
});
</script>

<template>
    <span class="tabular-nums">
        <span class="sr-only">{{ value }}</span>
        <span aria-hidden="true">{{ shown }}</span>
    </span>
</template>
