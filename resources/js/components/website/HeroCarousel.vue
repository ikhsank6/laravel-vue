<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Search } from '@lucide/vue';
import { computed, ref } from 'vue';
import SearchInput from '@/components/common/SearchInput.vue';
import { Button } from '@/components/ui/button';
import CertificatePreview from '@/components/website/CertificatePreview.vue';
import RosetteBackdrop from '@/components/website/RosetteBackdrop.vue';
import { fieldIcon } from '@/lib/icons';
import { index as schemeIndex } from '@/routes/schemes';
import type { Carousel } from '@/types';

const props = defineProps<{
    slides: Carousel[];
    fields: { id: number; name: string; slug: string }[];
}>();

// Search lives in the hero, beside the certificate: finding a scheme is the first job of a visitor.
const query = ref('');
const submitSearch = () => {
    const q = query.value.trim();

    if (q) {
        router.get(schemeIndex({ query: { q } }).url);
    }
};

const current = ref(0);
const slide = computed(() => props.slides[current.value]);
const hasMany = computed(() => props.slides.length > 1);

// Pointer parallax: the certificate leans toward the cursor and the rosette drifts the other
// way. Fine pointers only, never for people who ask for less motion.
const root = ref<HTMLElement | null>(null);
const px = ref(0);
const py = ref(0);
const canTrack =
    typeof window !== 'undefined' &&
    window.matchMedia('(hover: hover) and (pointer: fine)').matches &&
    !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
let frame = 0;

const onMove = (event: PointerEvent) => {
    if (!canTrack || !root.value) {
        return;
    }

    cancelAnimationFrame(frame);
    frame = requestAnimationFrame(() => {
        const box = root.value!.getBoundingClientRect();
        px.value = ((event.clientX - box.left) / box.width - 0.5) * 2;
        py.value = ((event.clientY - box.top) / box.height - 0.5) * 2;
    });
};

const resetTrack = () => {
    px.value = 0;
    py.value = 0;
};

const go = (index: number) => {
    current.value = (index + props.slides.length) % props.slides.length;
};
</script>

<template>
    <section
        ref="root"
        aria-roledescription="carousel"
        aria-label="Sorotan"
        class="tile-dark relative isolate overflow-hidden"
        :style="{ '--px': px, '--py': py }"
        @pointermove="onMove"
        @pointerleave="resetTrack"
    >
        <RosetteBackdrop
            class="parallax-bg absolute top-1/2 right-[-14%] -z-10 size-[min(130vw,1000px)] -translate-y-1/2 text-white/20"
        />
        <!-- Print registration marks: the corners of a sheet about to be printed. -->
        <span
            class="pointer-events-none absolute top-4 left-4 hidden size-5 border-t border-l border-white/35 lg:block"
            aria-hidden="true"
        />
        <span
            class="pointer-events-none absolute top-4 right-4 hidden size-5 border-t border-r border-white/35 lg:block"
            aria-hidden="true"
        />
        <div
            class="mx-auto grid max-w-6xl items-center gap-12 px-4 pt-14 pb-12 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16 lg:pt-20 lg:pb-14"
        >
            <div class="flex flex-col justify-center">
                <Transition
                    mode="out-in"
                    enter-active-class="transition-opacity duration-300 motion-reduce:transition-none"
                    leave-active-class="transition-opacity duration-150 motion-reduce:transition-none"
                    enter-from-class="opacity-0"
                    leave-to-class="opacity-0"
                >
                    <div
                        :key="slide.id"
                        role="group"
                        aria-roledescription="slide"
                        :aria-label="`${current + 1} dari ${slides.length}`"
                    >
                        <h1
                            class="rise font-display text-4xl leading-[1.08] font-semibold tracking-tight text-balance sm:text-5xl lg:text-[48px]"
                        >
                            {{ slide.title }}
                        </h1>
                        <p
                            class="rise mt-5 max-w-xl text-lg leading-relaxed text-muted-foreground sm:text-xl"
                            style="--rise-delay: 90ms"
                        >
                            {{ slide.subtitle }}
                        </p>
                        <Link
                            v-if="slide.link && slide.link_label"
                            :href="slide.link"
                            class="rise mt-4 inline-flex min-h-11 items-center text-link underline-offset-4 hover:underline"
                            style="--rise-delay: 150ms"
                        >
                            {{ slide.link_label }}
                        </Link>
                    </div>
                </Transition>

                <div class="rise mt-6" style="--rise-delay: 200ms">
                    <form
                        role="search"
                        class="flex max-w-xl gap-2 [&_button_svg]:text-primary-foreground [&_input]:border-white [&_input]:bg-white [&_input]:text-[#0f2a4a] [&_input]:placeholder:text-[#4a5f78] [&_svg]:text-[#4a5f78]"
                        @submit.prevent="submitSearch"
                    >
                        <SearchInput
                            v-model="query"
                            shape="pill"
                            label="Cari skema sertifikasi"
                            placeholder="Nama skema atau bidang kerja"
                            class="flex-1 sm:max-w-none"
                        />
                        <Button type="submit" size="lg">
                            <Search class="size-4" aria-hidden="true" />
                            Cari
                        </Button>
                    </form>
                    <ul
                        class="mt-3 flex max-w-xl flex-wrap gap-1.5"
                        aria-label="Bidang skema"
                    >
                        <li v-for="field in fields" :key="field.id">
                            <Link
                                :href="
                                    schemeIndex({
                                        query: { field: field.slug },
                                    })
                                "
                                class="flex min-h-11 items-center gap-1.5 rounded-md border border-white/25 px-3 text-[13px] text-foreground transition-colors duration-150 hover:bg-white/10 sm:min-h-10"
                            >
                                <component
                                    :is="fieldIcon(field.name)"
                                    class="size-4 shrink-0"
                                    aria-hidden="true"
                                />
                                {{ field.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="rise [perspective:1000px]" style="--rise-delay: 200ms">
                <div class="parallax-card">
                    <div class="float-slow">
                        <CertificatePreview />
                    </div>
                </div>
            </div>
        </div>

        <!-- Index tabs: the slides read as a table of contents. A ruler scale sits above them,
             the measuring instrument of an assessment. -->
        <nav
            v-if="hasMany"
            class="rise mx-auto max-w-6xl px-4 pb-14 sm:px-6 lg:pb-16"
            style="--rise-delay: 300ms"
            aria-label="Pilih slide"
        >
            <div class="border-t border-white/20" aria-hidden="true" />
            <ol
                class="grid auto-cols-[minmax(11rem,1fr)] grid-flow-col overflow-x-auto"
            >
                <li v-for="(s, i) in slides" :key="s.id" class="min-w-0">
                    <button
                        type="button"
                        class="group relative block min-h-11 w-full py-4 pr-5 text-left"
                        :aria-current="i === current ? 'true' : undefined"
                        :aria-label="`Tampilkan slide ${i + 1}: ${s.title}`"
                        @click="go(i)"
                    >
                        <span
                            :class="[
                                'absolute top-0 left-0 h-0.5 w-full origin-left bg-highlight transition-transform duration-300',
                                i === current
                                    ? 'scale-x-100'
                                    : 'scale-x-0 group-hover:scale-x-50',
                            ]"
                            aria-hidden="true"
                        />
                        <span
                            class="block text-xs text-muted-foreground tabular-nums"
                            >{{ String(i + 1).padStart(2, '0') }}</span
                        >
                        <span
                            :class="[
                                'mt-1 line-clamp-2 block text-sm leading-snug font-semibold transition-colors duration-200',
                                i === current
                                    ? 'text-foreground'
                                    : 'text-muted-foreground group-hover:text-foreground',
                            ]"
                            >{{ s.title }}</span
                        >
                    </button>
                </li>
            </ol>
        </nav>
    </section>
</template>
