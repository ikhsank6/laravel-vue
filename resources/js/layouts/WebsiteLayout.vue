<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { House, Info, Layers, Menu, Newspaper } from '@lucide/vue';
import { computed, ref } from 'vue';
import ThemeToggle from '@/components/common/ThemeToggle.vue';
import LogoMark from '@/components/LogoMark.vue';
import ScrollProgress from '@/components/website/ScrollProgress.vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { useScrolled } from '@/composables/useScrolled';
import { useSurface } from '@/composables/useSurface';
import { about, dashboard, home, login } from '@/routes';
import { index as newsIndex } from '@/routes/news';
import { index as schemeIndex } from '@/routes/schemes';

useSurface('website');

const page = usePage();
const appName = computed(() => page.props.name);
const isAuthenticated = computed(() => !!page.props.auth.user);
const { isCurrentUrl, isCurrentOrParentUrl } = useCurrentUrl();

const links = [
    { title: 'Beranda', href: home(), exact: true, icon: House },
    { title: 'Skema', href: schemeIndex(), exact: false, icon: Layers },
    { title: 'Berita', href: newsIndex(), exact: false, icon: Newspaper },
    { title: 'Tentang', href: about(), exact: true, icon: Info },
];

const isActive = (link: (typeof links)[number]) =>
    link.exact ? isCurrentUrl(link.href) : isCurrentOrParentUrl(link.href);

const menuOpen = ref(false);
const scrolled = useScrolled();
const year = new Date().getFullYear();
</script>

<template>
    <div class="flex min-h-svh flex-col bg-background">
        <a
            href="#konten"
            class="sr-only z-50 rounded-sm bg-primary px-4 py-2 font-semibold text-primary-foreground focus:not-sr-only focus:fixed focus:top-2 focus:left-2"
        >
            Lewati ke konten
        </a>

        <!-- Global nav: always black with white text, same in both themes. -->
        <header
            class="nav-3d sticky top-0 z-40 bg-frame text-white [--ring:#f2b01e]"
            :data-scrolled="scrolled"
        >
            <div
                class="mx-auto flex h-12 max-w-6xl items-center gap-2 px-4 sm:px-6"
            >
                <Link
                    :href="home()"
                    class="flex min-h-11 items-center gap-2.5 rounded-sm pr-4"
                >
                    <LogoMark class="size-8 shrink-0" />
                    <span class="font-display text-[17px] font-semibold">{{
                        appName
                    }}</span>
                </Link>

                <nav
                    class="hidden items-center md:flex"
                    aria-label="Navigasi utama"
                >
                    <Link
                        v-for="link in links"
                        :key="link.title"
                        :href="link.href"
                        :aria-current="isActive(link) ? 'page' : undefined"
                        :class="[
                            'nav-link flex min-h-11 items-center rounded-sm px-3 text-sm',
                            isActive(link)
                                ? 'font-semibold text-white'
                                : 'text-white/70 hover:text-white',
                        ]"
                    >
                        {{ link.title }}
                    </Link>
                </nav>

                <div class="ml-auto flex items-center gap-1">
                    <ThemeToggle />
                    <Button
                        variant="ghost"
                        class="hidden text-white/90 hover:bg-white/10 hover:text-white sm:inline-flex"
                        as-child
                    >
                        <Link :href="isAuthenticated ? dashboard() : login()">
                            {{ isAuthenticated ? 'Buka dashboard' : 'Masuk' }}
                        </Link>
                    </Button>

                    <Sheet v-model:open="menuOpen">
                        <SheetTrigger as-child>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="text-white/90 hover:bg-white/10 hover:text-white md:hidden"
                                aria-label="Buka menu"
                            >
                                <Menu class="size-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="right" class="w-72">
                            <SheetHeader>
                                <SheetTitle class="text-left font-semibold">{{
                                    appName
                                }}</SheetTitle>
                            </SheetHeader>
                            <nav
                                class="flex flex-col gap-1 px-4"
                                aria-label="Navigasi utama"
                            >
                                <Link
                                    v-for="link in links"
                                    :key="link.title"
                                    :href="link.href"
                                    :aria-current="
                                        isActive(link) ? 'page' : undefined
                                    "
                                    :class="[
                                        'flex min-h-12 items-center gap-3 rounded-sm px-3 text-base',
                                        isActive(link)
                                            ? 'bg-muted font-semibold text-foreground'
                                            : 'text-muted-foreground hover:text-foreground',
                                    ]"
                                    @click="menuOpen = false"
                                >
                                    <component
                                        :is="link.icon"
                                        class="size-5 shrink-0"
                                        aria-hidden="true"
                                    />
                                    {{ link.title }}
                                </Link>
                                <Link
                                    :href="
                                        isAuthenticated ? dashboard() : login()
                                    "
                                    class="mt-4 flex min-h-12 items-center rounded-sm px-3 text-base font-semibold text-link"
                                    @click="menuOpen = false"
                                >
                                    {{
                                        isAuthenticated
                                            ? 'Buka dashboard'
                                            : 'Masuk ke panel admin'
                                    }}
                                </Link>
                            </nav>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>
            <ScrollProgress />
        </header>

        <p
            class="tile-parchment px-4 py-2 text-center text-xs text-muted-foreground"
        >
            Data contoh. Ganti melalui panel CMS sebelum situs diluncurkan.
        </p>

        <main id="konten" class="flex-1">
            <!-- Keyed by page component, so filters on the same page do not remount it. -->
            <Transition name="page" mode="out-in">
                <div :key="page.component">
                    <slot />
                </div>
            </Transition>
        </main>

        <div class="rule-guilloche" aria-hidden="true" />
        <footer class="tile-dark text-sm">
            <div
                class="mx-auto flex max-w-6xl flex-col gap-1 px-4 py-3 sm:px-6 md:flex-row md:items-center md:justify-between"
            >
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                    <Link
                        :href="home()"
                        class="flex min-h-11 items-center gap-2.5 font-display text-base font-semibold"
                    >
                        <LogoMark class="size-7 shrink-0" />
                        {{ appName }}
                    </Link>
                    <p class="text-muted-foreground">
                        &copy; {{ year }} {{ appName }}
                    </p>
                </div>
                <nav class="flex flex-wrap gap-x-5" aria-label="Tautan footer">
                    <Link
                        v-for="link in links"
                        :key="link.title"
                        :href="link.href"
                        class="flex min-h-11 items-center text-muted-foreground transition-colors hover:text-foreground"
                    >
                        {{ link.title }}
                    </Link>
                    <Link
                        :href="isAuthenticated ? dashboard() : login()"
                        class="flex min-h-11 items-center text-muted-foreground transition-colors hover:text-foreground"
                    >
                        {{ isAuthenticated ? 'Dashboard' : 'Masuk admin' }}
                    </Link>
                </nav>
            </div>
        </footer>
    </div>
</template>
