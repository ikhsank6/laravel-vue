<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from '@lucide/vue';
import { computed } from 'vue';
import NewsCard from '@/components/website/NewsCard.vue';
import { formatDate, paragraphs } from '@/lib/format';
import { index as newsIndex } from '@/routes/news';
import type { News } from '@/types';

const props = defineProps<{
    article: News;
    related: News[];
}>();

const body = computed(() => paragraphs(props.article.content));
</script>

<template>
    <Head :title="article.title">
        <meta name="description" :content="article.excerpt" />
    </Head>

    <article class="tile-light px-4 pt-8 sm:px-6">
        <div class="mx-auto max-w-3xl">
            <Link
                :href="newsIndex()"
                class="-ml-2 inline-flex min-h-11 items-center gap-1 rounded-sm px-2 text-sm text-link underline-offset-4 hover:underline"
            >
                <ChevronLeft class="size-4" />
                Semua berita
            </Link>

            <p class="mt-6 text-sm text-muted-foreground">
                {{ article.category_name }},
                {{ formatDate(article.published_at) }}
            </p>
            <h1
                class="mt-2 text-4xl leading-[1.1] font-semibold tracking-tight text-balance sm:text-5xl"
            >
                {{ article.title }}
            </h1>
            <p class="mt-4 text-xl leading-snug text-muted-foreground">
                {{ article.excerpt }}
            </p>

            <div
                class="mt-8 max-w-[68ch] space-y-5 border-t border-border pt-8 font-display text-[17px] leading-relaxed sm:text-xl sm:leading-[1.7]"
            >
                <p v-for="(paragraph, i) in body" :key="i">{{ paragraph }}</p>
            </div>
        </div>
    </article>

    <section
        v-if="related.length"
        class="tile-parchment mt-16 px-4 py-16 sm:px-6"
        aria-labelledby="related-title"
    >
        <div class="mx-auto max-w-6xl">
            <h2
                id="related-title"
                class="mb-6 font-display text-3xl font-semibold tracking-tight"
            >
                Lainnya di {{ article.category_name }}
            </h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <NewsCard v-for="item in related" :key="item.id" :news="item" />
            </div>
        </div>
    </section>
</template>
