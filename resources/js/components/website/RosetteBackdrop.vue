<script setup lang="ts">
import { computed } from 'vue';

// Guilloche rosette: the interlaced hypotrochoid curves printed on certificates and banknotes
// as a security pattern. Three layers turn at different speeds so the lattice slowly shifts.
// Decorative only, drawn in the parent's text color, and still under reduced motion.
const curve = (R: number, r: number, d: number, turns: number) => {
    const steps = 720 * turns;
    const k = (R - r) / r;
    const points: string[] = [];

    for (let i = 0; i <= steps; i++) {
        const t = (i / 720) * Math.PI * 2;
        const x = (R - r) * Math.cos(t) + d * Math.cos(k * t);
        const y = (R - r) * Math.sin(t) - d * Math.sin(k * t);
        points.push(`${(300 + x).toFixed(1)},${(300 + y).toFixed(1)}`);
    }

    return `M${points.join('L')}Z`;
};

const layers = computed(() => [
    { d: curve(270, 27, 46, 1), class: 'spin-slow' },
    { d: curve(250, 25, 40, 1), class: 'spin-slow-rev' },
    { d: curve(220, 44, 64, 1), class: 'spin-slower' },
]);
</script>

<template>
    <svg
        viewBox="0 0 600 600"
        class="pointer-events-none [mask-image:radial-gradient(circle,#000_38%,transparent_72%)] select-none"
        aria-hidden="true"
        focusable="false"
    >
        <g
            fill="none"
            stroke="currentColor"
            stroke-width="0.7"
            vector-effect="non-scaling-stroke"
        >
            <path
                v-for="(layer, i) in layers"
                :key="i"
                :d="layer.d"
                :class="layer.class"
                vector-effect="non-scaling-stroke"
            />
        </g>
    </svg>
</template>
