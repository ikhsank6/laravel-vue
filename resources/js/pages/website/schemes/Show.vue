<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft, Gauge, ListChecks, Scale } from '@lucide/vue';
import type { Scheme } from '@/components/website/SchemeCard.vue';
import { index as schemeIndex } from '@/routes/schemes';

defineProps<{ scheme: Scheme }>();
</script>

<template>
    <Head :title="scheme.name" />

    <article class="px-4 pt-8 pb-20 sm:px-6">
        <div class="mx-auto max-w-3xl">
            <Link
                :href="schemeIndex()"
                class="-ml-2 inline-flex min-h-11 items-center gap-1 rounded-sm px-2 text-sm text-link underline-offset-4 hover:underline"
            >
                <ChevronLeft class="size-4" aria-hidden="true" />
                Semua skema
            </Link>

            <p class="mt-6 text-sm text-muted-foreground">
                {{ scheme.field }}, {{ scheme.level }}
            </p>
            <h1
                class="rise mt-2 font-display text-4xl leading-[1.1] font-semibold tracking-tight text-balance sm:text-5xl"
            >
                {{ scheme.name }}
            </h1>
            <p
                class="rise mt-4 text-xl leading-snug text-muted-foreground"
                style="--rise-delay: 80ms"
            >
                {{ scheme.summary }}
            </p>

            <dl
                class="mt-10 grid grid-cols-1 gap-6 border-t border-border pt-8 sm:grid-cols-3"
            >
                <div>
                    <dt
                        class="flex items-center gap-1.5 text-sm text-muted-foreground"
                    >
                        <Gauge class="size-4 shrink-0" aria-hidden="true" />
                        Jenjang
                    </dt>
                    <dd class="mt-1 font-semibold">{{ scheme.level }}</dd>
                </div>
                <div>
                    <dt
                        class="flex items-center gap-1.5 text-sm text-muted-foreground"
                    >
                        <ListChecks
                            class="size-4 shrink-0"
                            aria-hidden="true"
                        />
                        Unit kompetensi
                    </dt>
                    <dd class="mt-1 font-semibold tabular-nums">
                        {{ scheme.units }}
                    </dd>
                </div>
                <div>
                    <dt
                        class="flex items-center gap-1.5 text-sm text-muted-foreground"
                    >
                        <Scale class="size-4 shrink-0" aria-hidden="true" />
                        Metode uji
                    </dt>
                    <dd class="mt-1 font-semibold">{{ scheme.method }}</dd>
                </div>
            </dl>

            <p
                class="tile-parchment mt-10 rounded-lg p-5 text-sm text-muted-foreground"
            >
                Halaman ini memakai data contoh. Persyaratan, biaya, dan jadwal
                resmi skema belum dicantumkan.
            </p>
        </div>
    </article>
</template>
