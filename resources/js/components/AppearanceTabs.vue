<script setup lang="ts">
import { Monitor, Moon, Sun } from '@lucide/vue';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Terang' },
    { value: 'dark', Icon: Moon, label: 'Gelap' },
    { value: 'system', Icon: Monitor, label: 'Ikuti sistem' },
] as const;
</script>

<template>
    <div
        role="radiogroup"
        aria-label="Tema"
        class="inline-flex gap-1 rounded-sm bg-muted p-1"
    >
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            type="button"
            role="radio"
            :aria-checked="appearance === value"
            @click="updateAppearance(value)"
            :class="[
                'flex min-h-11 items-center rounded-xs px-3.5 text-sm transition-colors sm:min-h-9',
                appearance === value
                    ? 'bg-card font-semibold text-foreground'
                    : 'text-muted-foreground hover:text-foreground',
            ]"
        >
            <component :is="Icon" class="size-4" aria-hidden="true" />
            <span class="ml-1.5">{{ label }}</span>
        </button>
    </div>
</template>
