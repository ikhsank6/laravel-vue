<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core';
import { computed } from 'vue';

// Thin gold line on the navbar edge showing how far down the page you are. It orients the
// reader on long pages and moves only as fast as they scroll.
const { y } = useWindowScroll();

const progress = computed(() => {
    if (typeof document === 'undefined') {
        return 0;
    }

    const max = document.documentElement.scrollHeight - window.innerHeight;

    return max > 0 ? Math.min(Math.max(y.value / max, 0), 1) : 0;
});
</script>

<template>
    <span
        class="pointer-events-none absolute inset-x-0 bottom-0 h-0.5 origin-left bg-highlight"
        :style="{ transform: `scaleX(${progress})` }"
        aria-hidden="true"
    />
</template>
