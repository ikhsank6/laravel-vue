<script setup lang="ts">
import { Eye, EyeOff } from '@lucide/vue';
import { computed, ref, useAttrs, useTemplateRef } from 'vue';
import type { Component, HTMLAttributes } from 'vue';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';

defineOptions({ inheritAttrs: false });

const props = defineProps<{
    class?: HTMLAttributes['class'];
    autofocus?: boolean;
    /** Optional leading lucide icon, same look as IconInput. Turns red when aria-invalid. */
    icon?: Component;
}>();

const attrs = useAttrs();
const invalid = computed(
    () => attrs['aria-invalid'] === true || attrs['aria-invalid'] === 'true',
);

const showPassword = ref(false);
const inputRef = useTemplateRef('inputRef');

defineExpose({
    $el: inputRef,
    focus: () => inputRef.value?.$el?.focus(),
});
</script>

<template>
    <div class="relative">
        <component
            :is="icon"
            v-if="icon"
            :class="
                cn(
                    'pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground',
                    invalid && 'text-destructive',
                )
            "
            aria-hidden="true"
        />
        <Input
            v-focus="props.autofocus"
            ref="inputRef"
            :type="showPassword ? 'text' : 'password'"
            :class="cn(icon ? 'pr-10 pl-9' : 'pr-10', props.class)"
            v-bind="$attrs"
        />
        <button
            type="button"
            @click="showPassword = !showPassword"
            :class="
                cn(
                    'absolute inset-y-0 right-0 flex items-center rounded-r-md px-3 text-muted-foreground hover:text-foreground focus-visible:ring-[3px] focus-visible:ring-ring focus-visible:outline-none',
                )
            "
            :aria-label="
                showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
            "
            :tabindex="-1"
        >
            <EyeOff v-if="showPassword" class="size-4" />
            <Eye v-else class="size-4" />
        </button>
    </div>
</template>
