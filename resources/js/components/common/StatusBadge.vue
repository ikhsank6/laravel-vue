<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';

export type StatusTone = 'success' | 'neutral' | 'warning' | 'danger' | 'info';

const props = withDefaults(
    defineProps<{
        tone?: StatusTone;
        label: string;
    }>(),
    { tone: 'neutral' },
);

// The label always carries the meaning; color only reinforces it.
const variant = computed(
    () =>
        ({
            success: 'success',
            neutral: 'secondary',
            warning: 'warning',
            danger: 'destructive',
            info: 'default',
        })[props.tone] as
            | 'success'
            | 'secondary'
            | 'warning'
            | 'destructive'
            | 'default',
);
</script>

<template>
    <Badge :variant="variant">{{ label }}</Badge>
</template>
