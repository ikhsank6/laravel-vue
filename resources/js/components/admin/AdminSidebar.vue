<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronDown, Globe } from '@lucide/vue';
import { computed, reactive } from 'vue';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import { useAdminNavigation } from '@/composables/useAdminNavigation';
import { home } from '@/routes';

// shadcn Sidebar: collapses to icons on desktop, becomes a sheet on mobile. Groups are
// collapsible, every page stays one click away, and the active page carries a gold marker.
const { groups, isActive } = useAdminNavigation();
const { isMobile, setOpenMobile, state } = useSidebar();

// In icon mode there is no room for group labels, so every group opens: a collapsed group
// would hide its pages and the user could not tell they exist.
const iconMode = computed(() => state.value === 'collapsed' && !isMobile.value);
const openGroups = reactive<Record<string, boolean>>({});
const isOpen = (label: string) => iconMode.value || (openGroups[label] ?? true);

const closeOnMobile = () => {
    if (isMobile.value) {
        setOpenMobile(false);
    }
};

// Gold marker grows from the middle when a page becomes active (a state change, so it moves).
const itemClass =
    'relative before:absolute before:top-1.5 before:bottom-1.5 before:left-0 before:w-0.5 before:scale-y-0 before:rounded-full before:bg-highlight before:transition-transform before:duration-200 data-[active=true]:before:scale-y-100 data-[active=true]:font-semibold min-h-11 md:min-h-8 [&>svg]:transition-transform [&>svg]:duration-200 hover:[&>svg]:translate-x-0.5';
</script>

<template>
    <Sidebar collapsible="icon" aria-label="Menu admin">
        <SidebarContent class="gap-1 py-3">
            <template v-for="group in groups" :key="group.label ?? 'root'">
                <SidebarGroup v-if="!group.label" class="py-0">
                    <SidebarGroupContent>
                        <SidebarMenu>
                            <SidebarMenuItem
                                v-for="item in group.items"
                                :key="item.title"
                            >
                                <SidebarMenuButton
                                    as-child
                                    :is-active="isActive(item.href)"
                                    :tooltip="item.title"
                                    :class="itemClass"
                                >
                                    <Link
                                        :href="item.href"
                                        :aria-current="
                                            isActive(item.href)
                                                ? 'page'
                                                : undefined
                                        "
                                        @click="closeOnMobile"
                                    >
                                        <component :is="item.icon" />
                                        <span>{{ item.title }}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>

                <Collapsible
                    v-else
                    :open="isOpen(group.label)"
                    :disabled="iconMode"
                    as-child
                    class="group/collapsible"
                    @update:open="openGroups[group.label] = $event"
                >
                    <SidebarGroup
                        class="py-1 group-data-[collapsible=icon]:mt-1 group-data-[collapsible=icon]:border-t group-data-[collapsible=icon]:border-sidebar-border group-data-[collapsible=icon]:pt-2"
                    >
                        <SidebarGroupLabel as-child>
                            <CollapsibleTrigger
                                class="flex min-h-11 w-full items-center text-xs font-semibold text-sidebar-foreground/70 hover:text-sidebar-foreground md:min-h-8"
                            >
                                {{ group.label }}
                                <ChevronDown
                                    class="ml-auto size-4 transition-colors duration-200 group-data-[state=open]/collapsible:rotate-180"
                                    aria-hidden="true"
                                />
                            </CollapsibleTrigger>
                        </SidebarGroupLabel>
                        <CollapsibleContent>
                            <SidebarGroupContent>
                                <SidebarMenu>
                                    <SidebarMenuItem
                                        v-for="item in group.items"
                                        :key="item.title"
                                    >
                                        <SidebarMenuButton
                                            as-child
                                            :is-active="isActive(item.href)"
                                            :tooltip="item.title"
                                            :class="itemClass"
                                        >
                                            <Link
                                                :href="item.href"
                                                :aria-current="
                                                    isActive(item.href)
                                                        ? 'page'
                                                        : undefined
                                                "
                                                @click="closeOnMobile"
                                            >
                                                <component :is="item.icon" />
                                                <span>{{ item.title }}</span>
                                            </Link>
                                        </SidebarMenuButton>
                                    </SidebarMenuItem>
                                </SidebarMenu>
                            </SidebarGroupContent>
                        </CollapsibleContent>
                    </SidebarGroup>
                </Collapsible>
            </template>
        </SidebarContent>

        <SidebarFooter class="border-t border-sidebar-border">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        as-child
                        tooltip="Lihat website"
                        class="min-h-11 md:min-h-8"
                    >
                        <a
                            :href="home().url"
                            target="_blank"
                            rel="noopener"
                            aria-label="Lihat website (tab baru)"
                        >
                            <Globe />
                            <span>Lihat website</span>
                        </a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarFooter>
    </Sidebar>
</template>
