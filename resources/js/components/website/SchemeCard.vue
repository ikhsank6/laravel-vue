<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { fieldIcon } from '@/lib/icons';
import { show } from '@/routes/schemes';

export type Scheme = {
    id: number;
    slug: string;
    name: string;
    field: string;
    level: string;
    level_no: number;
    units: number;
    summary: string;
    method: string;
};

const props = defineProps<{ scheme: Scheme; featured?: boolean }>();

// The card draws its own data: a nine-step staircase lit up to the KKNI level, and one square
// per competency unit the scheme assesses.
const steps = computed(() =>
    Array.from({ length: 9 }, (_, i) => ({
        on: i < props.scheme.level_no,
        height: 5 + i * 2,
    })),
);
</script>

<template>
    <article
        :class="[
            'group relative flex flex-col gap-4 rounded-lg border border-border bg-card p-5 transition-colors duration-200 hover:border-primary/60',
            featured ? 'sm:p-8' : '',
        ]"
    >
        <div class="flex items-center justify-between gap-3">
            <span
                class="flex min-w-0 items-center gap-1.5 text-sm text-muted-foreground"
            >
                <component
                    :is="fieldIcon(scheme.field)"
                    class="size-4 shrink-0 text-primary"
                    aria-hidden="true"
                />
                <span class="truncate">{{ scheme.field }}</span>
            </span>
            <span
                class="flex items-end gap-1.5"
                role="img"
                :aria-label="scheme.level"
            >
                <span class="flex h-5 items-end gap-px" aria-hidden="true">
                    <span
                        v-for="(step, i) in steps"
                        :key="i"
                        :class="[
                            'w-[3px] rounded-t-[1px] transition-colors duration-200',
                            step.on
                                ? 'bg-primary group-hover:bg-highlight'
                                : 'bg-border',
                        ]"
                        :style="{ height: `${step.height}px` }"
                    />
                </span>
                <span
                    class="text-xs leading-none font-semibold text-muted-foreground"
                    aria-hidden="true"
                    >{{ scheme.level.replace('KKNI level ', '') }}</span
                >
            </span>
        </div>

        <div class="min-w-0 flex-1">
            <h3
                :class="[
                    'leading-snug font-semibold',
                    featured ? 'text-2xl sm:text-3xl' : 'line-clamp-2 text-lg',
                ]"
            >
                <Link
                    :href="show(scheme.slug)"
                    class="after:absolute after:inset-0 after:rounded-lg"
                >
                    {{ scheme.name }}
                </Link>
            </h3>
            <p
                :class="[
                    'mt-2 text-muted-foreground',
                    featured ? 'text-base' : 'line-clamp-2 text-sm',
                ]"
            >
                {{ scheme.summary }}
            </p>
        </div>

        <div class="border-t border-border pt-3">
            <span class="flex flex-wrap gap-1" aria-hidden="true">
                <span
                    v-for="n in scheme.units"
                    :key="n"
                    class="size-2.5 rounded-xs bg-primary/25 transition-colors duration-200 group-hover:bg-primary/60"
                />
            </span>
            <p class="mt-2 text-sm text-muted-foreground tabular-nums">
                {{ scheme.units }} unit kompetensi
            </p>
        </div>
    </article>
</template>
