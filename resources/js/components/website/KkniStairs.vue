<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

// KKNI has nine qualification levels. Each step is a link when a scheme sits on that level
// and a dimmed outline when none does, so the staircase doubles as the level filter.
const props = defineProps<{
    levels: { no: number; count: number }[];
    hrefFor: (level: number | null) => string;
    active?: number | null;
}>();

const ROMAN = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'];

const steps = computed(() =>
    props.levels.map((level, i) => ({
        ...level,
        roman: ROMAN[level.no - 1],
        height: 44 + i * 13,
        delay: i * 55,
        label: `Jenjang ${level.no}, ${
            level.count ? `${level.count} skema` : 'belum ada skema'
        }`,
    })),
);

const stepClass = (step: (typeof steps.value)[number]) => [
    'stair-step on-reveal flex h-full w-full flex-col items-center justify-start rounded-t-xs pt-2 text-sm font-semibold transition-colors duration-150',
    props.active === step.no
        ? 'bg-primary text-primary-foreground'
        : step.count
          ? 'border-t-2 border-highlight bg-primary/15 text-primary hover:bg-primary/25 dark:text-link'
          : 'border border-dashed border-input text-muted-foreground',
];
</script>

<template>
    <div>
        <ol class="flex items-end gap-1.5 sm:gap-2" aria-label="Jenjang KKNI">
            <li
                v-for="step in steps"
                :key="step.no"
                class="min-w-0 flex-1"
                :style="{ height: `${step.height}px` }"
            >
                <Link
                    v-if="step.count"
                    :href="hrefFor(active === step.no ? null : step.no)"
                    :class="stepClass(step)"
                    :style="{ '--d': `${step.delay}ms` }"
                    :aria-label="step.label"
                    :aria-current="active === step.no ? 'true' : undefined"
                    preserve-scroll
                >
                    <span aria-hidden="true">{{ step.roman }}</span>
                    <span
                        class="mt-0.5 text-xs font-normal tabular-nums"
                        aria-hidden="true"
                        >{{ step.count }}</span
                    >
                </Link>
                <div
                    v-else
                    :class="stepClass(step)"
                    :style="{ '--d': `${step.delay}ms` }"
                    role="img"
                    :aria-label="step.label"
                >
                    <span aria-hidden="true">{{ step.roman }}</span>
                </div>
            </li>
        </ol>
        <p class="mt-3 text-sm text-muted-foreground">
            Angka di bawah jenjang adalah jumlah skema contoh. Pilih satu
            jenjang untuk menyaring daftar.
        </p>
    </div>
</template>
