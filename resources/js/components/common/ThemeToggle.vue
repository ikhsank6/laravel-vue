<script setup lang="ts">
import { Moon, Sun } from '@lucide/vue';
import { computed } from 'vue';
import { useAppearance } from '@/composables/useAppearance';

// Light/dark switch for the dark bars (website nav, admin navbar, auth backdrop). A real
// switch (role=switch): the thumb slides to the active side and shows the active theme.
const { resolvedAppearance, updateAppearance } = useAppearance();

const isDark = computed(() => resolvedAppearance.value === 'dark');
const label = computed(() =>
    isDark.value ? 'Ganti ke tema terang' : 'Ganti ke tema gelap',
);
</script>

<template>
    <button
        type="button"
        role="switch"
        :aria-checked="isDark"
        :aria-label="label"
        :title="label"
        class="group inline-flex min-h-11 min-w-11 items-center justify-center rounded-full [--ring:#f2b01e]"
        @click="updateAppearance(isDark ? 'light' : 'dark')"
    >
        <span
            :class="[
                'relative flex h-8 w-[58px] items-center rounded-full border border-white/25 transition-colors duration-300 group-hover:border-white/50',
                isDark ? 'bg-black/30' : 'bg-white/15',
            ]"
        >
            <Sun
                class="absolute left-2 size-4 text-white/55"
                aria-hidden="true"
            />
            <Moon
                class="absolute right-2 size-4 text-white/55"
                aria-hidden="true"
            />
            <span
                :class="[
                    'absolute top-0.5 left-0.5 grid size-6 place-items-center rounded-full text-[#0f2a4a] shadow-[0_1px_3px_rgb(0_0_0/0.4)] transition-[transform,background-color] duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none',
                    isDark
                        ? 'translate-x-7 bg-[#eaf2fb]'
                        : 'translate-x-0 bg-highlight',
                ]"
            >
                <Moon v-if="isDark" class="size-3.5" aria-hidden="true" />
                <Sun v-else class="size-3.5" aria-hidden="true" />
            </span>
        </span>
    </button>
</template>
