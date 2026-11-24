<script setup lang="ts">
import { Search, X } from '@lucide/vue';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const model = defineModel<string>({ default: '' });

const props = withDefaults(
    defineProps<{
        class?: HTMLAttributes['class'];
        label?: string;
        placeholder?: string;
        /** `pill` is the website search; `field` matches admin form fields. */
        shape?: 'field' | 'pill';
    }>(),
    { label: 'Cari', placeholder: 'Cari...', shape: 'field' },
);
</script>

<template>
    <div
        :class="
            cn(
                'relative w-full',
                shape === 'pill' ? 'sm:max-w-xs' : 'sm:w-64',
                props.class,
            )
        "
    >
        <Search
            :class="
                cn(
                    'pointer-events-none absolute top-1/2 size-4 -translate-y-1/2 text-muted-foreground',
                    shape === 'pill' ? 'left-4' : 'left-3',
                )
            "
            aria-hidden="true"
        />
        <input
            v-model="model"
            type="search"
            name="q"
            :aria-label="label"
            :placeholder="placeholder"
            :class="
                cn(
                    'w-full text-sm placeholder:text-muted-foreground focus-visible:outline-none [&::-webkit-search-cancel-button]:hidden',
                    shape === 'pill'
                        ? 'h-11 rounded-md border border-input bg-card pr-11 pl-11 focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring'
                        : 'h-11 rounded-[var(--field-radius)] border border-input bg-card pr-9 pl-9 focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring sm:h-9',
                )
            "
        />
        <button
            v-if="model"
            type="button"
            class="absolute top-1/2 right-1 flex size-8 -translate-y-1/2 items-center justify-center rounded-md text-muted-foreground hover:text-foreground"
            aria-label="Hapus pencarian"
            @click="model = ''"
        >
            <X class="size-4" />
        </button>
    </div>
</template>
