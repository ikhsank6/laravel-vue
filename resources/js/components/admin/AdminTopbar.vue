<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import ThemeToggle from '@/components/common/ThemeToggle.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import UserInfo from '@/components/UserInfo.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useScrolled } from '@/composables/useScrolled';
import ScrollProgress from '@/components/website/ScrollProgress.vue';
import { dashboard } from '@/routes';

// One raised navy bar across the whole top, the same as the website: the logo lives in it, and
// the trigger beside the logo collapses and expands only the sidebar below (Ctrl/Cmd+B).
// Theme and account sit on the right.
const user = computed(() => usePage().props.auth.user);
const scrolled = useScrolled();
</script>

<template>
    <header
        class="nav-3d fixed inset-x-0 top-0 z-30 flex h-14 items-center gap-2 bg-frame px-3 text-white [--ring:#f2b01e] sm:px-5"
        :data-scrolled="scrolled"
    >
        <Link
            :href="dashboard()"
            class="flex min-h-11 min-w-0 items-center gap-2.5 rounded-sm pr-2"
        >
            <AppLogo />
        </Link>

        <SidebarTrigger
            class="size-11 shrink-0 text-white/80 hover:bg-white/10 hover:text-white md:size-9"
            aria-label="Ciutkan atau perluas sidebar"
        />

        <div class="ml-auto flex items-center gap-1">
            <ThemeToggle />
            <DropdownMenu>
                <DropdownMenuTrigger
                    class="ml-1 flex min-h-11 items-center gap-1.5 rounded-sm px-1 text-white md:min-h-9 max-sm:[&>div]:hidden"
                    aria-label="Menu akun"
                >
                    <UserInfo :user="user" />
                    <ChevronDown
                        class="size-3.5 text-white/70"
                        aria-hidden="true"
                    />
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-60">
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <ScrollProgress />
    </header>
</template>
