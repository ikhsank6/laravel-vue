<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed } from 'vue';

const props = defineProps<{
    page: number;
    lastPage: number;
    /** Builds the URL for a page number, keeping the current filters. */
    hrefFor: (page: number) => string;
}>();

const pages = computed(() =>
    Array.from({ length: props.lastPage }, (_, i) => i + 1),
);

const itemClass =
    'flex size-11 items-center justify-center rounded-md text-sm font-semibold transition-colors';
</script>

<template>
    <nav
        v-if="lastPage > 1"
        class="flex items-center justify-center gap-1"
        aria-label="Halaman berita"
    >
        <Link
            v-if="page > 1"
            :href="hrefFor(page - 1)"
            :class="[
                itemClass,
                'text-muted-foreground hover:bg-muted hover:text-foreground',
            ]"
            aria-label="Halaman sebelumnya"
            preserve-scroll
        >
            <ChevronLeft class="size-5" />
        </Link>
        <Link
            v-for="n in pages"
            :key="n"
            :href="hrefFor(n)"
            :aria-current="n === page ? 'page' : undefined"
            :class="[
                itemClass,
                n === page
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:bg-muted hover:text-foreground',
            ]"
        >
            {{ n }}
        </Link>
        <Link
            v-if="page < lastPage"
            :href="hrefFor(page + 1)"
            :class="[
                itemClass,
                'text-muted-foreground hover:bg-muted hover:text-foreground',
            ]"
            aria-label="Halaman berikutnya"
        >
            <ChevronRight class="size-5" />
        </Link>
    </nav>
</template>
