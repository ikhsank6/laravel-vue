<script setup lang="ts">
import { computed } from 'vue';

// Sample logo mark (not an official logo): the scalloped seal also used on the certificate,
// holding a blue disc with a check. Gold rim, blue disc and white check read on both the
// navy bars and light surfaces, so it needs no theme variant.
const seal = computed(() => {
    const points = Array.from({ length: 56 }, (_, i) => {
        const r = i % 2 === 0 ? 48 : 43.5;
        const a = (Math.PI * 2 * i) / 56 - Math.PI / 2;

        return `${(50 + r * Math.cos(a)).toFixed(2)},${(50 + r * Math.sin(a)).toFixed(2)}`;
    });

    return `M${points.join('L')}Z`;
});
</script>

<template>
    <svg viewBox="0 0 100 100" aria-hidden="true" focusable="false">
        <path :d="seal" fill="#f2b01e" />
        <circle cx="50" cy="50" r="36" fill="#2563a8" />
        <circle
            cx="50"
            cy="50"
            r="32"
            fill="none"
            stroke="#ffffff"
            stroke-opacity="0.35"
            stroke-width="1"
        />
        <path
            d="M32 51l13 13 24-28"
            fill="none"
            stroke="#ffffff"
            stroke-width="7"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>
</template>
