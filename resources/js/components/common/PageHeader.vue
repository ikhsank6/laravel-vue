<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useAdminNavigation } from '@/composables/useAdminNavigation';
import { dashboard } from '@/routes';

defineProps<{
    title: string;
    description?: string;
}>();

// Breadcrumb comes from the navigation config, so pages never repeat it.
const { currentGroup } = useAdminNavigation();
</script>

<template>
    <div class="page-tide">
        <header
            class="flex flex-col gap-3 py-5 sm:flex-row sm:items-center sm:justify-between lg:py-6"
        >
            <div class="min-w-0 space-y-1">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        {{ title }}
                    </h1>
                    <nav
                        v-if="title !== 'Dashboard'"
                        aria-label="Breadcrumb"
                        class="text-sm text-muted-foreground"
                    >
                        <ol class="flex flex-wrap items-center gap-1.5">
                            <li>
                                <Link
                                    :href="dashboard()"
                                    class="hover:text-foreground"
                                    >Dashboard</Link
                                >
                            </li>
                            <template v-if="currentGroup?.label">
                                <li aria-hidden="true">/</li>
                                <li>{{ currentGroup.label }}</li>
                            </template>
                            <li aria-hidden="true">/</li>
                            <li aria-current="page" class="text-foreground">
                                {{ title }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <p
                    v-if="description"
                    class="max-w-3xl text-sm text-muted-foreground"
                >
                    {{ description }}
                </p>
            </div>
            <div
                v-if="$slots.actions"
                class="flex shrink-0 flex-wrap items-center gap-2"
            >
                <slot name="actions" />
            </div>
        </header>
    </div>
</template>
