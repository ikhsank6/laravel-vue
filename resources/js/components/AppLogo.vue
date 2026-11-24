<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import LogoMark from '@/components/LogoMark.vue';

// Logo lockup for the dark admin navbar: sample mark plus the institution name in the display
// serif.
const name = computed(() => usePage().props.name);
</script>

<template>
    <LogoMark class="size-8 shrink-0" />
    <span class="truncate font-display text-[17px] font-semibold text-white">{{
        name
    }}</span>
</template>
