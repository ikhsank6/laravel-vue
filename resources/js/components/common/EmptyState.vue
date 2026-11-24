<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';

defineProps<{
    icon?: LucideIcon;
    title: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex flex-col items-center justify-center gap-3 px-6 py-14 text-center"
    >
        <component
            :is="icon"
            v-if="icon"
            class="size-8 text-muted-foreground"
            aria-hidden="true"
        />
        <div class="space-y-1">
            <p class="font-semibold">{{ title }}</p>
            <p
                v-if="description"
                class="mx-auto max-w-sm text-sm text-muted-foreground"
            >
                {{ description }}
            </p>
        </div>
        <slot />
    </div>
</template>
