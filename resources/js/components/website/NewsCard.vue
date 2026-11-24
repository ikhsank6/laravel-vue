<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import CoverArt from '@/components/common/CoverArt.vue';
import { formatDate } from '@/lib/format';
import { show } from '@/routes/news';
import type { News } from '@/types';

defineProps<{ news: News }>();
</script>

<template>
    <article
        class="group relative rounded-lg border border-border bg-card p-4 transition-colors duration-150 hover:bg-accent sm:p-5"
    >
        <CoverArt :title="news.title" size="lg" class="mb-4" />
        <p class="text-sm text-muted-foreground">
            {{ news.category_name }}, {{ formatDate(news.published_at) }}
        </p>
        <h3 class="mt-1 line-clamp-2 text-lg leading-snug font-semibold">
            <Link
                :href="show(news.slug)"
                class="after:absolute after:inset-0 after:rounded-lg"
            >
                {{ news.title }}
            </Link>
        </h3>
        <p class="mt-1 line-clamp-2 text-sm text-muted-foreground">
            {{ news.excerpt }}
        </p>
    </article>
</template>
