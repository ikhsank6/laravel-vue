<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    FilePenLine,
    GalleryHorizontal,
    ListChecks,
    Newspaper,
    TriangleAlert,
    Users,
} from '@lucide/vue';
import { computed } from 'vue';
import CountUp from '@/components/common/CountUp.vue';
import CoverArt from '@/components/common/CoverArt.vue';
import DemoNotice from '@/components/common/DemoNotice.vue';
import EmptyState from '@/components/common/EmptyState.vue';
import PageHeader from '@/components/common/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { formatDate, formatDateTime } from '@/lib/format';
import { home } from '@/routes';
import { index as carouselsIndex } from '@/routes/admin/carousels';
import { index as failedJobsIndex } from '@/routes/admin/failed-jobs';
import { index as newsIndex } from '@/routes/admin/news';
import { index as usersIndex } from '@/routes/admin/users';
import { show as newsShow } from '@/routes/news';
import type { FailedJob, News } from '@/types';

const props = defineProps<{
    drafts: News[];
    recentlyPublished: News[];
    failedJobs: FailedJob[];
    byCategory: { name: string; count: number }[];
    counts: {
        published: number;
        drafts: number;
        activeSlides: number;
        users: number;
    };
}>();

const firstName = computed(() => usePage().props.auth.user.name.split(' ')[0]);

// One strip of four figures. Each cell is a shortcut to the page that manages that content,
// and its hint is a plain description of what the figure counts.
const stats = computed(() => [
    {
        label: 'Berita terbit',
        value: props.counts.published,
        hint: `dari ${props.counts.published + props.counts.drafts} berita`,
        href: newsIndex(),
        icon: Newspaper,
    },
    {
        label: 'Draf berita',
        value: props.counts.drafts,
        hint: 'belum diterbitkan',
        href: newsIndex(),
        icon: FilePenLine,
    },
    {
        label: 'Slide aktif',
        value: props.counts.activeSlides,
        hint: 'tampil di beranda',
        href: carouselsIndex(),
        icon: GalleryHorizontal,
    },
    {
        label: 'Pengguna',
        value: props.counts.users,
        hint: 'terdaftar di panel',
        href: usersIndex(),
        icon: Users,
    },
]);

// Colors come from the chart tokens; the legend names every segment, so color is never alone.
const SEGMENTS = [
    'bg-chart-1',
    'bg-highlight',
    'bg-chart-3',
    'bg-chart-4',
    'bg-chart-5',
];
const total = computed(() =>
    props.byCategory.reduce((sum, item) => sum + item.count, 0),
);
const segmentClass = (i: number) => SEGMENTS[i % SEGMENTS.length];

const stagger = (i: number) => ({ '--rise-delay': `${i * 60}ms` });
</script>

<template>
    <Head title="Dashboard" />

    <PageHeader
        title="Dashboard"
        :description="`Halo, ${firstName}. Berikut yang perlu ditinjau hari ini.`"
    >
        <template #actions>
            <Button variant="outline" as-child>
                <a :href="home().url" target="_blank" rel="noopener">
                    Lihat website
                    <span class="sr-only">(tab baru)</span>
                </a>
            </Button>
        </template>
    </PageHeader>

    <div class="space-y-5">
        <DemoNotice>
            Data contoh dari tahap UI. Angka dihitung dari data contoh yang
            sama.
        </DemoNotice>

        <!-- Summary strip: hairlines come from a 1px gap over a border-colored backdrop. -->
        <ul
            class="rise grid grid-cols-2 gap-px overflow-hidden rounded-lg border border-border bg-border lg:grid-cols-4"
            aria-label="Ringkasan konten"
        >
            <li v-for="stat in stats" :key="stat.label" class="bg-card">
                <Link
                    :href="stat.href"
                    class="group block h-full p-5 transition-colors duration-150 hover:bg-muted"
                >
                    <span
                        class="flex items-center justify-between text-sm text-muted-foreground"
                    >
                        {{ stat.label }}
                        <component
                            :is="stat.icon"
                            class="size-4"
                            aria-hidden="true"
                        />
                    </span>
                    <span
                        class="mt-2 block text-3xl leading-none font-semibold tracking-tight"
                    >
                        <CountUp :value="stat.value" />
                    </span>
                    <span class="mt-2 block text-xs text-muted-foreground">{{
                        stat.hint
                    }}</span>
                </Link>
            </li>
        </ul>

        <div
            class="grid grid-cols-[minmax(0,1fr)] items-start gap-5 lg:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]"
        >
            <!-- One work queue: drafts to publish, then jobs that failed. -->
            <section
                class="card-surface rise divide-y divide-border"
                style="--rise-delay: 120ms"
                aria-labelledby="action-title"
            >
                <header
                    class="flex items-center justify-between gap-3 px-5 py-4"
                >
                    <h2 id="action-title" class="text-lg font-semibold">
                        Perlu tindakan
                    </h2>
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="newsIndex()">Semua berita</Link>
                    </Button>
                </header>

                <div class="px-5 py-4">
                    <h3 class="text-sm font-semibold text-muted-foreground">
                        Draf menunggu terbit ({{ counts.drafts }})
                    </h3>
                    <ul v-if="drafts.length" class="-mx-2 mt-2">
                        <li
                            v-for="draft in drafts"
                            :key="draft.id"
                            class="flex items-center gap-3 rounded-md p-2 transition-colors hover:bg-muted"
                        >
                            <CoverArt :title="draft.title" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold">
                                    {{ draft.title }}
                                </p>
                                <p class="mt-0.5 text-xs text-muted-foreground">
                                    {{ draft.category_name }}, diubah
                                    {{ formatDateTime(draft.updated_at) }}
                                </p>
                            </div>
                            <Button variant="outline" size="sm" as-child>
                                <Link :href="newsIndex()">
                                    Tinjau
                                    <span class="sr-only">{{
                                        draft.title
                                    }}</span>
                                </Link>
                            </Button>
                        </li>
                    </ul>
                    <EmptyState
                        v-else
                        :icon="FilePenLine"
                        title="Tidak ada draf"
                        description="Semua berita sudah terbit. Draf baru dari penulis akan muncul di sini."
                    />
                </div>

                <div class="px-5 py-4">
                    <h3 class="text-sm font-semibold text-muted-foreground">
                        Antrian
                    </h3>
                    <div
                        v-if="failedJobs.length"
                        class="mt-2 flex flex-wrap items-center gap-3"
                    >
                        <TriangleAlert
                            class="size-5 shrink-0 text-destructive"
                            aria-hidden="true"
                        />
                        <p class="min-w-0 flex-1">
                            <strong class="font-semibold"
                                >{{ failedJobs.length }} job gagal.</strong
                            >
                            <span class="text-muted-foreground">
                                Terakhir
                                {{ failedJobs[0].job.split('\\').pop() }},
                                {{ formatDateTime(failedJobs[0].failed_at) }}.
                            </span>
                        </p>
                        <Button variant="outline" size="sm" as-child>
                            <Link :href="failedJobsIndex()"
                                >Tinjau antrian</Link
                            >
                        </Button>
                    </div>
                    <p
                        v-else
                        class="mt-2 flex items-center gap-2 text-muted-foreground"
                    >
                        <ListChecks
                            class="size-5 text-success"
                            aria-hidden="true"
                        />
                        Semua job berjalan normal.
                    </p>
                </div>
            </section>

            <div class="space-y-5">
                <section
                    class="card-surface rise p-5"
                    style="--rise-delay: 180ms"
                    aria-labelledby="category-title"
                >
                    <h2 id="category-title" class="text-lg font-semibold">
                        Berita terbit per kategori
                    </h2>

                    <template v-if="total">
                        <div
                            class="mt-4 flex h-2.5 gap-1 overflow-hidden rounded-sm"
                            role="img"
                            :aria-label="`${total} berita terbit, dibagi ${byCategory.length} kategori`"
                        >
                            <span
                                v-for="(item, i) in byCategory"
                                :key="item.name"
                                :class="['h-full rounded-xs', segmentClass(i)]"
                                :style="{ flexGrow: item.count }"
                            />
                        </div>
                        <ul class="mt-3 divide-y divide-border">
                            <li
                                v-for="(item, i) in byCategory"
                                :key="item.name"
                                class="flex items-center gap-3 py-2.5"
                            >
                                <span
                                    :class="[
                                        'size-2.5 shrink-0 rounded-xs',
                                        segmentClass(i),
                                    ]"
                                    aria-hidden="true"
                                />
                                <span class="flex-1">{{ item.name }}</span>
                                <span class="font-semibold tabular-nums">{{
                                    item.count
                                }}</span>
                            </li>
                        </ul>
                    </template>
                    <EmptyState
                        v-else
                        :icon="Newspaper"
                        title="Belum ada berita terbit"
                        description="Distribusi kategori muncul setelah ada berita yang diterbitkan."
                    />
                </section>

                <section
                    class="card-surface rise p-5"
                    style="--rise-delay: 240ms"
                    aria-labelledby="published-title"
                >
                    <h2 id="published-title" class="text-lg font-semibold">
                        Terbit terakhir
                    </h2>
                    <ul v-if="recentlyPublished.length" class="-mx-2 mt-2">
                        <li v-for="item in recentlyPublished" :key="item.id">
                            <a
                                :href="newsShow(item.slug).url"
                                target="_blank"
                                rel="noopener"
                                class="block rounded-md p-2 transition-colors hover:bg-muted"
                            >
                                <p class="line-clamp-1 text-sm font-semibold">
                                    {{ item.title }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{ formatDate(item.published_at)
                                    }}<span class="sr-only"
                                        >, buka di tab baru</span
                                    >
                                </p>
                            </a>
                        </li>
                    </ul>
                    <p v-else class="mt-2 text-sm text-muted-foreground">
                        Belum ada berita yang terbit.
                    </p>
                </section>
            </div>
        </div>
    </div>
</template>
