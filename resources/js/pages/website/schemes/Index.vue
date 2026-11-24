<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { SearchX } from '@lucide/vue';
import { ref } from 'vue';
import EmptyState from '@/components/common/EmptyState.vue';
import SearchInput from '@/components/common/SearchInput.vue';
import { Button } from '@/components/ui/button';
import KkniStairs from '@/components/website/KkniStairs.vue';
import SchemeCard from '@/components/website/SchemeCard.vue';
import type { Scheme } from '@/components/website/SchemeCard.vue';
import { fieldIcon } from '@/lib/icons';
import { index as schemeIndex } from '@/routes/schemes';

type Filters = { field: string | null; level: number | null; q: string };
type Field = { id: number; name: string; slug: string };

const props = defineProps<{
    schemes: Scheme[];
    fields: Field[];
    levels: { no: number; count: number }[];
    filters: Filters;
}>();

const search = ref(props.filters.q);

const urlFor = (overrides: Partial<Filters>) => {
    const query = { ...props.filters, ...overrides };

    return schemeIndex({
        query: {
            field: query.field || undefined,
            level: query.level || undefined,
            q: query.q || undefined,
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
    'flex min-h-11 shrink-0 items-center gap-2 rounded-md border px-4 text-sm transition-colors duration-150',
    active
        ? 'border-primary bg-primary font-semibold text-primary-foreground'
        : 'border-input bg-card text-foreground hover:bg-accent',
];
</script>

<template>
    <Head title="Skema sertifikasi" />

    <section class="tile-parchment px-4 py-14 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <h1
                class="rise font-display text-4xl font-semibold tracking-tight sm:text-5xl"
            >
                Skema sertifikasi
            </h1>
            <p
                class="rise mt-3 max-w-2xl text-xl text-muted-foreground"
                style="--rise-delay: 80ms"
            >
                Pilih skema sesuai bidang kerja Anda. Daftar ini contoh dan
                belum memuat persyaratan resmi.
            </p>
        </div>
    </section>
    <div class="rule-guilloche" aria-hidden="true" />

    <div class="tile-light px-4 pt-12 pb-20 sm:px-6">
        <div class="mx-auto max-w-6xl space-y-8">
            <div class="rounded-lg bg-muted p-5 sm:p-6">
                <KkniStairs
                    :levels="levels"
                    :active="filters.level"
                    :href-for="(level) => urlFor({ level })"
                />
            </div>
            <div
                class="flex flex-col-reverse gap-4 md:flex-row md:items-center md:justify-between"
            >
                <nav
                    class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 md:mx-0 md:px-0"
                    aria-label="Bidang skema"
                >
                    <Link
                        :href="urlFor({ field: null })"
                        :class="chipClass(!filters.field)"
                        :aria-current="!filters.field ? 'page' : undefined"
                        preserve-scroll
                    >
                        Semua bidang
                    </Link>
                    <Link
                        v-for="field in fields"
                        :key="field.id"
                        :href="urlFor({ field: field.slug })"
                        :class="chipClass(filters.field === field.slug)"
                        :aria-current="
                            filters.field === field.slug ? 'page' : undefined
                        "
                        preserve-scroll
                    >
                        <component
                            :is="fieldIcon(field.name)"
                            class="size-4 shrink-0"
                            aria-hidden="true"
                        />
                        {{ field.name }}
                    </Link>
                </nav>
                <form
                    role="search"
                    class="w-full md:w-auto"
                    @submit.prevent="submitSearch"
                >
                    <SearchInput
                        v-model="search"
                        shape="pill"
                        label="Cari skema"
                        placeholder="Cari nama skema..."
                    />
                </form>
            </div>

            <p
                v-if="filters.q"
                class="text-sm text-muted-foreground"
                aria-live="polite"
            >
                {{ schemes.length }} hasil untuk "{{ filters.q }}"
            </p>

            <div
                v-if="schemes.length"
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
                <SchemeCard
                    v-for="(scheme, i) in schemes"
                    :key="scheme.id"
                    v-reveal="i % 3"
                    :scheme="scheme"
                />
            </div>
            <div v-else class="card-surface border border-border bg-card">
                <EmptyState
                    :icon="SearchX"
                    title="Tidak ada skema yang cocok"
                    description="Coba kata kunci lain atau lihat semua bidang."
                >
                    <Button variant="secondary" size="sm" as-child>
                        <Link
                            :href="urlFor({ field: null, level: null, q: '' })"
                            >Lihat semua skema</Link
                        >
                    </Button>
                </EmptyState>
            </div>
        </div>
    </div>
</template>
