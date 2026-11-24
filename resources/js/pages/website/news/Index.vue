<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { SearchX } from '@lucide/vue';
import { ref } from 'vue';
import EmptyState from '@/components/common/EmptyState.vue';
import SearchInput from '@/components/common/SearchInput.vue';
import LinkPagination from '@/components/website/LinkPagination.vue';
import NewsCard from '@/components/website/NewsCard.vue';
import { Button } from '@/components/ui/button';
import { index as newsIndex } from '@/routes/news';
import type { News, NewsCategory } from '@/types';

type Filters = { category: string | null; q: string };

const props = defineProps<{
    news: News[];
    categories: NewsCategory[];
    filters: Filters;
    pagination: { page: number; lastPage: number; total: number };
}>();

const search = ref(props.filters.q);

const urlFor = (overrides: Partial<Filters> & { page?: number }) => {
    const query = { ...props.filters, ...overrides };

    return newsIndex({
        query: {
            category: query.category || undefined,
            q: query.q || undefined,
            page: query.page && query.page > 1 ? query.page : undefined,
        },
    }).url;
};

const submitSearch = () =>
    router.get(
        urlFor({ q: search.value.trim() }),
        {},
        { preserveScroll: true },
    );

const chipClass = (active: boolean) => [
    'flex min-h-11 shrink-0 items-center rounded-md border px-4 text-sm transition-colors duration-150',
    active
        ? 'border-primary bg-primary font-semibold text-primary-foreground'
        : 'border-input bg-card text-foreground hover:bg-accent',
];
</script>

<template>
    <Head title="Berita" />

    <section class="tile-parchment px-4 py-14 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <h1
                class="rise font-display text-4xl font-semibold tracking-tight sm:text-5xl"
            >
                Berita
            </h1>
            <p class="mt-3 text-xl text-muted-foreground">
                Pengumuman, panduan, dan catatan rilis.
            </p>
        </div>
    </section>
    <div class="rule-guilloche" aria-hidden="true" />

    <div class="tile-light px-4 pt-12 pb-20 sm:px-6">
        <div class="mx-auto max-w-6xl space-y-8">
            <div
                class="flex flex-col-reverse gap-4 md:flex-row md:items-center md:justify-between"
            >
                <nav
                    class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 md:mx-0 md:px-0"
                    aria-label="Kategori berita"
                >
                    <Link
                        :href="urlFor({ category: null, page: 1 })"
                        :class="chipClass(!filters.category)"
                        :aria-current="!filters.category ? 'page' : undefined"
                        preserve-scroll
                    >
                        Semua
                    </Link>
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="urlFor({ category: category.slug, page: 1 })"
                        :class="chipClass(filters.category === category.slug)"
                        :aria-current="
                            filters.category === category.slug
                                ? 'page'
                                : undefined
                        "
                        preserve-scroll
                    >
                        {{ category.name }}
                    </Link>
                </nav>
                <form
                    role="search"
                    class="w-full md:w-auto"
                    @submit.prevent="submitSearch"
                >
                    <SearchInput
                        shape="pill"
                        v-model="search"
                        label="Cari berita"
                        placeholder="Cari judul berita..."
                    />
                </form>
            </div>

            <p
                v-if="filters.q"
                class="text-sm text-muted-foreground"
                aria-live="polite"
            >
                {{ pagination.total }} hasil untuk "{{ filters.q }}"
            </p>

            <div
                v-if="news.length"
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
                <NewsCard
                    v-for="(item, i) in news"
                    :key="item.id"
                    v-reveal="i % 3"
                    :news="item"
                />
            </div>
            <div v-else class="card-surface border border-border bg-card">
                <EmptyState
                    :icon="SearchX"
                    title="Tidak ada berita yang cocok"
                    :description="
                        filters.q || filters.category
                            ? 'Coba kata kunci lain atau lihat semua kategori.'
                            : 'Belum ada berita yang diterbitkan.'
                    "
                >
                    <Button
                        v-if="filters.q || filters.category"
                        variant="secondary"
                        size="sm"
                        as-child
                    >
                        <Link :href="urlFor({ category: null, q: '', page: 1 })"
                            >Lihat semua berita</Link
                        >
                    </Button>
                </EmptyState>
            </div>

            <LinkPagination
                :page="pagination.page"
                :last-page="pagination.lastPage"
                :href-for="(page) => urlFor({ page })"
            />
        </div>
    </div>
</template>
