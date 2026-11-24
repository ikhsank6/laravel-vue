<script setup lang="ts">
import { CircleAlert } from '@lucide/vue';

// Field error: icon plus text (never color alone), announced to screen readers, and it eases
// in so the change is noticed. Pair it with aria-invalid and aria-describedby on the input.
defineProps<{
    message?: string;
    id?: string;
}>();
</script>

<template>
    <Transition
        enter-active-class="transition duration-200 ease-out motion-reduce:transition-none"
        enter-from-class="-translate-y-1 opacity-0"
        leave-active-class="transition duration-100 ease-in motion-reduce:transition-none"
        leave-to-class="opacity-0"
    >
        <p
            v-if="message"
            :id="id"
            role="alert"
            class="flex items-start gap-1.5 text-sm text-destructive"
        >
            <CircleAlert class="mt-0.5 size-4 shrink-0" aria-hidden="true" />
            <span>{{ message }}</span>
        </p>
    </Transition>
</template>
