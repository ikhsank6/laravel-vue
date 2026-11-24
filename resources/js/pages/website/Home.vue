<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    Award,
    ClipboardPen,
    ListChecks,
    Mail,
    MapPin,
    Newspaper,
    PencilRuler,
    Phone,
    Scale,
} from '@lucide/vue';
import { computed } from 'vue';
import EmptyState from '@/components/common/EmptyState.vue';
import { Button } from '@/components/ui/button';
import HeroCarousel from '@/components/website/HeroCarousel.vue';
import KkniStairs from '@/components/website/KkniStairs.vue';
import RosetteBackdrop from '@/components/website/RosetteBackdrop.vue';
import SchemeCard from '@/components/website/SchemeCard.vue';
import type { Scheme } from '@/components/website/SchemeCard.vue';
import { about as aboutPage } from '@/routes';
import { index as newsIndex, show as newsShow } from '@/routes/news';
import { index as schemeIndex } from '@/routes/schemes';
import type { About, Carousel, News } from '@/types';

type Field = { id: number; name: string; slug: string };

const props = defineProps<{
    slides: Carousel[];
    latestNews: News[];
    about: About;
    featuredSchemes: Scheme[];
    schemeFields: Field[];
    levels: { no: number; count: number }[];
}>();

const featured = computed(() => props.latestNews[0]);
const others = computed(() => props.latestNews.slice(1));
const aboutLead = computed(() => props.about.description.split('\n\n')[0]);

const contacts = computed(() =>
    [
        {
            label: 'Alamat',
            icon: MapPin,
            value: props.about.address,
            href: null,
        },
        {
            label: 'Email',
            icon: Mail,
            value: props.about.email,
            href: props.about.email ? `mailto:${props.about.email}` : null,
        },
        {
            label: 'Telepon',
            icon: Phone,
            value: props.about.phone,
            href: props.about.phone
                ? `tel:${props.about.phone.replace(/\s+/g, '')}`
                : null,
        },
    ].filter((c) => c.value),
);

const levelHref = (level: number | null) =>
    schemeIndex({ query: { level: level ?? undefined } }).url;

// The path a participant walks, in order. The sequence is the content, so numbers mean something.
const steps = [
    {
        title: 'Pendaftaran',
        icon: ClipboardPen,
        text: 'Pilih skema, isi formulir, dan unggah dokumen persyaratan.',
    },
    {
        title: 'Asesmen mandiri',
        icon: ListChecks,
        text: 'Nilai diri Anda terhadap setiap unit kompetensi pada skema.',
    },
    {
        title: 'Uji kompetensi',
        icon: PencilRuler,
        text: 'Asesor menilai lewat praktik, observasi, dan wawancara.',
    },
    {
        title: 'Keputusan asesor',
        icon: Scale,
        text: 'Asesor menyatakan Anda kompeten atau belum kompeten.',
    },
    {
        title: 'Sertifikat',
        icon: Award,
        text: 'Peserta yang dinyatakan kompeten menerima sertifikat kompetensi.',
    },
];

const dateParts = (value: string | null) => {
    const d = value ? new Date(value) : null;

    return d
        ? {
              day: d.toLocaleDateString('id-ID', { day: 'numeric' }),
              month: d.toLocaleDateString('id-ID', { month: 'short' }),
          }
        : { day: '', month: '' };
};

const heading =
    'font-display text-3xl font-semibold tracking-tight text-balance sm:text-[40px] sm:leading-[1.1]';
</script>

<template>
    <Head title="Beranda" />

    <HeroCarousel
        v-if="slides.length"
        :slides="slides"
        :fields="schemeFields"
    />

    <!-- Schemes, with the KKNI staircase as the level filter. -->
    <section
        class="tile-light px-4 py-24 sm:px-6"
        aria-labelledby="schemes-title"
    >
        <div class="mx-auto max-w-6xl">
            <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-end">
                <div v-reveal>
                    <h2 id="schemes-title" :class="heading">
                        Skema sertifikasi
                    </h2>
                    <p class="mt-3 max-w-md text-lg text-muted-foreground">
                        Setiap skema menetapkan unit kompetensi yang diuji dan
                        berada pada satu jenjang KKNI.
                    </p>
                    <Link
                        :href="schemeIndex()"
                        class="mt-4 inline-flex min-h-11 items-center gap-1 text-link underline-offset-4 hover:underline"
                    >
                        Lihat semua skema
                        <ArrowRight class="size-4" aria-hidden="true" />
                    </Link>
                </div>
                <div v-reveal="1" class="rounded-lg bg-muted p-5 sm:p-6">
                    <KkniStairs :levels="levels" :href-for="levelHref" />
                </div>
            </div>

            <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <SchemeCard
                    v-for="(scheme, i) in featuredSchemes"
                    :key="scheme.id"
                    v-reveal="i"
                    :scheme="scheme"
                    :featured="i === 0"
                    :class="i === 0 ? 'sm:col-span-2' : ''"
                />
            </div>
        </div>
    </section>

    <div class="rule-guilloche" aria-hidden="true" />

    <!-- The certification path, drawn as a path. -->
    <section
        id="alur"
        class="tile-dark relative isolate overflow-hidden px-4 py-24 sm:px-6"
        aria-labelledby="steps-title"
    >
        <RosetteBackdrop
            class="absolute -bottom-56 -left-48 -z-10 size-[680px] text-white/15"
        />
        <div class="mx-auto max-w-6xl">
            <div v-reveal class="mb-14 max-w-2xl">
                <h2 id="steps-title" :class="heading">
                    Lima tahap menuju sertifikat
                </h2>
                <p class="mt-3 text-lg text-muted-foreground">
                    Urutan umum yang dilalui peserta. Detail tiap skema bisa
                    berbeda.
                </p>
            </div>

            <ol v-reveal class="relative grid gap-10 md:grid-cols-5 md:gap-6">
                <!-- Path line behind the nodes (desktop). Draws once when scrolled to. -->
                <span
                    class="path-line on-reveal absolute top-6 right-[10%] left-[10%] hidden h-px bg-highlight/70 md:block"
                    aria-hidden="true"
                />
                <li
                    v-for="(step, i) in steps"
                    :key="step.title"
                    class="relative flex gap-5 md:flex-col md:items-center md:gap-4 md:text-center"
                >
                    <span
                        :class="[
                            'path-node on-reveal relative z-10 flex size-12 shrink-0 items-center justify-center rounded-md border-2',
                            i === steps.length - 1
                                ? 'border-highlight bg-highlight text-[#0f2a4a]'
                                : 'border-highlight bg-[var(--tile)] text-foreground',
                        ]"
                        :style="{ '--d': `${300 + i * 220}ms` }"
                    >
                        <component
                            :is="step.icon"
                            class="size-5"
                            aria-hidden="true"
                        />
                        <span class="sr-only">Tahap {{ i + 1 }}</span>
                    </span>
                    <div>
                        <p
                            class="text-sm text-muted-foreground tabular-nums"
                            aria-hidden="true"
                        >
                            Tahap {{ i + 1 }}
                        </p>
                        <h3 class="text-xl font-semibold">{{ step.title }}</h3>
                        <p class="mt-1.5 text-muted-foreground">
                            {{ step.text }}
                        </p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    <!-- News as a bulletin: the date leads each item. -->
    <section
        class="tile-parchment px-4 py-24 sm:px-6"
        aria-labelledby="latest-title"
    >
        <div class="mx-auto max-w-6xl">
            <div v-reveal class="mb-10 flex items-end justify-between gap-4">
                <h2 id="latest-title" :class="heading">
                    Berita dan pengumuman
                </h2>
                <Link
                    :href="newsIndex()"
                    class="flex min-h-11 shrink-0 items-center gap-1 text-link underline-offset-4 hover:underline"
                >
                    Semua berita
                    <ArrowRight class="size-4" aria-hidden="true" />
                </Link>
            </div>

            <div v-if="featured" class="grid gap-4 lg:grid-cols-5">
                <article
                    v-reveal
                    class="group relative flex flex-col justify-between gap-10 rounded-lg bg-[var(--tile)] p-6 text-[#eaf2fb] transition-colors duration-200 sm:p-8 lg:col-span-3"
                >
                    <div class="flex items-center gap-4">
                        <span
                            class="flex size-16 flex-col items-center justify-center rounded-md bg-highlight leading-none text-[#0f2a4a]"
                        >
                            <span class="text-2xl font-semibold tabular-nums">{{
                                dateParts(featured.published_at).day
                            }}</span>
                            <span class="mt-0.5 text-xs font-semibold">{{
                                dateParts(featured.published_at).month
                            }}</span>
                        </span>
                        <p class="text-sm text-[#9db6d1]">
                            {{ featured.category_name }}
                        </p>
                    </div>
                    <div>
                        <h3
                            class="font-display text-2xl leading-tight font-semibold sm:text-[32px]"
                        >
                            <Link
                                :href="newsShow(featured.slug)"
                                class="after:absolute after:inset-0 after:rounded-lg"
                            >
                                {{ featured.title }}
                            </Link>
                        </h3>
                        <p class="mt-3 line-clamp-3 text-[#9db6d1]">
                            {{ featured.excerpt }}
                        </p>
                    </div>
                </article>

                <ul
                    v-if="others.length"
                    class="grid content-start gap-3 lg:col-span-2"
                >
                    <li
                        v-for="(item, i) in others"
                        :key="item.id"
                        v-reveal="i + 1"
                    >
                        <Link
                            :href="newsShow(item.slug)"
                            class="group flex items-start gap-4 rounded-lg bg-card p-4 transition-colors duration-200 hover:bg-accent motion-reduce:hover:translate-y-0"
                        >
                            <span
                                class="flex size-14 shrink-0 flex-col items-center justify-center rounded-md bg-muted leading-none"
                            >
                                <span
                                    class="text-xl font-semibold tabular-nums"
                                    >{{
                                        dateParts(item.published_at).day
                                    }}</span
                                >
                                <span
                                    class="mt-0.5 text-xs text-muted-foreground"
                                    >{{
                                        dateParts(item.published_at).month
                                    }}</span
                                >
                            </span>
                            <span class="min-w-0">
                                <span
                                    class="block text-sm text-muted-foreground"
                                    >{{ item.category_name }}</span
                                >
                                <span
                                    class="mt-0.5 line-clamp-2 block leading-snug font-semibold"
                                    >{{ item.title }}</span
                                >
                            </span>
                        </Link>
                    </li>
                </ul>
            </div>

            <div v-else class="rounded-lg bg-card">
                <EmptyState
                    :icon="Newspaper"
                    title="Belum ada berita"
                    description="Berita yang diterbitkan dari panel CMS akan tampil di sini."
                />
            </div>
        </div>
    </section>

    <!-- About and contact: who to talk to. Kept plain on purpose. -->
    <section
        class="tile-light px-4 py-20 sm:px-6 sm:py-24"
        aria-labelledby="about-title"
    >
        <div
            class="mx-auto grid max-w-6xl items-start gap-10 lg:grid-cols-[1.3fr_1fr] lg:gap-16"
        >
            <div v-reveal class="max-w-xl">
                <h2
                    id="about-title"
                    class="font-display text-2xl font-semibold tracking-tight text-balance sm:text-3xl"
                >
                    Tentang {{ about.name }}
                </h2>
                <p class="mt-4 leading-relaxed text-muted-foreground">
                    {{ aboutLead }}
                </p>
                <div class="mt-7 flex flex-wrap items-center gap-x-6 gap-y-3">
                    <Button as-child>
                        <Link :href="aboutPage()">Baca profil lembaga</Link>
                    </Button>
                    <Link
                        :href="schemeIndex()"
                        class="group inline-flex min-h-11 items-center gap-1.5 font-semibold text-link underline-offset-4 hover:underline"
                    >
                        Lihat skema
                        <ArrowRight
                            class="size-4 transition-transform group-hover:translate-x-0.5"
                            aria-hidden="true"
                        />
                    </Link>
                </div>
            </div>

            <aside
                v-if="contacts.length"
                v-reveal="1"
                class="rounded-lg border border-border bg-card"
                aria-labelledby="contact-title"
            >
                <h3
                    id="contact-title"
                    class="border-b border-border px-5 py-4 text-base font-semibold"
                >
                    Hubungi lembaga
                </h3>
                <dl class="divide-y divide-border">
                    <div
                        v-for="c in contacts"
                        :key="c.label"
                        class="flex items-start gap-4 px-5 py-4"
                    >
                        <span
                            class="flex size-9 shrink-0 items-center justify-center rounded-md bg-muted text-primary"
                        >
                            <component
                                :is="c.icon"
                                class="size-4"
                                aria-hidden="true"
                            />
                        </span>
                        <div class="min-w-0">
                            <dt class="text-xs text-muted-foreground">
                                {{ c.label }}
                            </dt>
                            <dd class="mt-0.5 text-sm break-words">
                                <a
                                    v-if="c.href"
                                    :href="c.href"
                                    class="font-semibold text-link underline-offset-4 hover:underline"
                                    >{{ c.value }}</a
                                >
                                <span v-else class="font-semibold">{{
                                    c.value
                                }}</span>
                            </dd>
                        </div>
                    </div>
                </dl>
            </aside>
        </div>
    </section>
</template>
