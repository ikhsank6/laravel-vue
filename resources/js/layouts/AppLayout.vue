<script setup lang="ts">
import AdminSidebar from '@/components/admin/AdminSidebar.vue';
import AdminTopbar from '@/components/admin/AdminTopbar.vue';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import { Toaster } from '@/components/ui/sonner';
import { useSurface } from '@/composables/useSurface';

useSurface('admin');
</script>

<template>
    <SidebarProvider class="pt-14" style="--sidebar-top: 3.5rem">
        <a
            href="#konten"
            class="sr-only z-50 rounded-sm bg-primary px-4 py-2 text-primary-foreground focus:not-sr-only focus:fixed focus:top-2 focus:left-2"
        >
            Lewati ke konten
        </a>
        <AdminTopbar />
        <AdminSidebar />
        <!-- @container lets the page header band bleed to the edges of this area (cqw). -->
        <SidebarInset class="@container min-w-0">
            <div
                id="konten"
                class="mx-auto w-full max-w-[1320px] flex-1 px-4 pb-12 sm:px-6 lg:px-8"
            >
                <slot />
            </div>
        </SidebarInset>
        <Toaster />
    </SidebarProvider>
</template>
