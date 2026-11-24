<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import ThemeToggle from '@/components/common/ThemeToggle.vue';
import LogoMark from '@/components/LogoMark.vue';
import AssessmentIllustration from '@/components/website/AssessmentIllustration.vue';
import RosetteBackdrop from '@/components/website/RosetteBackdrop.vue';
import { useSurface } from '@/composables/useSurface';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();

useSurface('admin');

const appName = usePage().props.name;
</script>

<template>
    <!-- The brand backdrop runs edge to edge; the form sits in its own box on the right.
         The box is outside .tile-dark, so form controls keep the normal theme tokens. -->
    <div class="relative isolate min-h-svh overflow-hidden bg-[var(--tile)]">
        <RosetteBackdrop
            class="absolute top-1/2 left-1/3 -z-10 size-[1100px] -translate-x-1/2 -translate-y-1/2 text-white/15"
        />

        <ThemeToggle
            class="absolute top-4 right-4 z-10 [--ring:#f2b01e] sm:top-5 sm:right-5"
        />

        <div
            class="mx-auto grid min-h-svh max-w-[1500px] lg:grid-cols-[1fr_minmax(440px,520px)] lg:gap-10 xl:gap-20"
        >
            <!-- Brand side (desktop) -->
            <aside
                class="tile-dark hidden flex-col justify-between bg-transparent p-12 lg:flex"
            >
                <Link
                    :href="home()"
                    class="rise flex w-fit items-center gap-3 rounded-sm font-display text-xl font-semibold"
                >
                    <LogoMark class="size-10 shrink-0" />
                    {{ appName }}
                </Link>

                <div>
                    <h2
                        class="rise max-w-lg font-display text-4xl leading-[1.12] font-semibold tracking-tight text-balance xl:text-[44px]"
                        style="--rise-delay: 100ms"
                    >
                        Kelola konten lembaga dari satu tempat.
                    </h2>
                    <div class="rise mt-8 max-w-xl" style="--rise-delay: 220ms">
                        <AssessmentIllustration />
                    </div>
                </div>

                <div>
                    <div
                        class="rule-guilloche mb-5 max-w-xs"
                        aria-hidden="true"
                    />
                    <p class="text-sm text-muted-foreground">
                        Panel admin {{ appName }}. Data di panel ini masih data
                        contoh.
                    </p>
                </div>
            </aside>

            <!-- Form side: the login box -->
            <main
                id="konten"
                class="flex items-start justify-center px-5 py-16 sm:items-center sm:px-8 lg:py-14 lg:pr-16 lg:pl-0"
            >
                <div class="rise w-full max-w-[460px]">
                    <!-- Logo for small screens, where the brand side is hidden. -->
                    <Link
                        :href="home()"
                        class="mb-6 flex w-fit items-center gap-2.5 rounded-sm font-display text-lg font-semibold text-white lg:hidden"
                    >
                        <LogoMark class="size-9 shrink-0" />
                        {{ appName }}
                    </Link>

                    <div
                        class="rounded-lg bg-card p-6 text-card-foreground shadow-[0_24px_48px_-24px_rgb(0_0_0/0.6)] sm:p-10"
                    >
                        <div class="mb-8 space-y-2">
                            <h1
                                class="font-display text-3xl leading-tight font-semibold tracking-tight"
                            >
                                {{ title }}
                            </h1>
                            <p class="text-muted-foreground">
                                {{ description }}
                            </p>
                        </div>
                        <slot />
                    </div>

                    <p class="mt-5 text-center text-sm">
                        <Link
                            :href="home()"
                            class="inline-flex min-h-11 items-center text-white/80 underline underline-offset-4 hover:text-white"
                        >
                            Lihat website
                        </Link>
                    </p>
                </div>
            </main>
        </div>
    </div>
</template>
