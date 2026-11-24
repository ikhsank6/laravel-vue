<script setup lang="ts">
import type { Component, HTMLAttributes } from 'vue';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';

/**
 * Input with a leading lucide icon. Attributes (id, name, type, aria-*, autocomplete...)
 * go to the <input>, so it is a drop-in for `Input`. The icon turns red when the field is
 * `aria-invalid`.
 */
defineOptions({ inheritAttrs: false });

const model = defineModel<string | number>({ default: '' });

const props = defineProps<{
    icon: Component;
    class?: HTMLAttributes['class'];
    invalid?: boolean;
}>();
</script>

<template>
    <div :class="cn('relative w-full', props.class)">
        <component
            :is="icon"
            :class="
                cn(
                    'pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground',
                    (invalid ||
                        $attrs['aria-invalid'] === true ||
                        $attrs['aria-invalid'] === 'true') &&
                        'text-destructive',
                )
            "
            aria-hidden="true"
        />
        <Input v-bind="$attrs" v-model="model" class="pl-9" />
    </div>
</template>
