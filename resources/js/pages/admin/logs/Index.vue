<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ScrollText, SearchX } from '@lucide/vue';
import { computed, ref, toRef } from 'vue';
import DataTable from '@/components/common/DataTable.vue';
import DemoNotice from '@/components/common/DemoNotice.vue';
import EmptyState from '@/components/common/EmptyState.vue';
import PageHeader from '@/components/common/PageHeader.vue';
import SearchInput from '@/components/common/SearchInput.vue';
import StatusBadge from '@/components/common/StatusBadge.vue';
import type { StatusTone } from '@/components/common/StatusBadge.vue';
import TablePagination from '@/components/common/TablePagination.vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { useDeferredProp } from '@/composables/useDeferredProp';
import { useTableQuery } from '@/composables/useTableQuery';
import { formatDateTime } from '@/lib/format';
import type { Column, LogEntry, LogLevel } from '@/types';

const props = defineProps<{ rows?: LogEntry[] }>();

const LEVELS: Record<LogLevel, { label: string; tone: StatusTone }> = {
    error: { label: 'Error', tone: 'danger' },
    warning: { label: 'Peringatan', tone: 'warning' },
    info: { label: 'Info', tone: 'info' },
};

const { isLoading, isError, retry } = useDeferredProp(
    'rows',
    toRef(props, 'rows'),
);

const level = ref<'all' | LogLevel>('all');
const visible = computed(() =>
    [...(props.rows ?? [])]
        .filter((row) => level.value === 'all' || row.level === level.value)
        .sort((a, b) => b.logged_at.localeCompare(a.logged_at)),
);
const { search, page, perPage, filtered, paged, lastPage } = useTableQuery(
    visible,
    {
        searchKeys: ['message', 'context'],
    },
);

const columns: Column[] = [
    { key: 'level', label: 'Level' },
    { key: 'message', label: 'Pesan', class: 'whitespace-normal' },
    { key: 'logged_at', label: 'Waktu', hideBelow: 'md' },
];

const selected = ref<LogEntry | null>(null);
const detailOpen = computed({
    get: () => selected.value !== null,
    set: (open) => {
        if (!open) {
            selected.value = null;
        }
    },
});

const prettyContext = computed(() => {
    try {
        return JSON.stringify(
            JSON.parse(selected.value?.context ?? '{}'),
            null,
            2,
        );
    } catch {
        return selected.value?.context ?? '';
    }
});
</script>

<template>
    <Head title="Log" />

    <PageHeader
        title="Log"
        description="Catatan kejadian aplikasi, terbaru di atas. Mulai dari error saat ada laporan masalah."
    />

    <div class="space-y-5">
        <DemoNotice>Data contoh. Versi final membaca storage/logs.</DemoNotice>

        <DataTable
            caption="Daftar log"
            :columns="columns"
            :rows="paged"
            :loading="isLoading"
            :error="isError"
            @retry="retry"
        >
            <template #header>
                <h2 class="text-base font-semibold">
                    Kejadian
                    <span class="ml-1 text-sm font-normal text-muted-foreground"
                        >({{ filtered.length }})</span
                    >
                </h2>
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <Tabs v-model="level">
                        <TabsList aria-label="Saring level log">
                            <TabsTrigger value="all">Semua</TabsTrigger>
                            <TabsTrigger value="error">Error</TabsTrigger>
                            <TabsTrigger value="warning"
                                >Peringatan</TabsTrigger
                            >
                            <TabsTrigger value="info">Info</TabsTrigger>
                        </TabsList>
                    </Tabs>
                    <SearchInput
                        v-model="search"
                        label="Cari log"
                        placeholder="Cari pesan atau konteks..."
                    />
                </div>
            </template>
            <template #cell-level="{ row }">
                <StatusBadge
                    :tone="LEVELS[row.level].tone"
                    :label="LEVELS[row.level].label"
                />
            </template>
            <template #cell-message="{ row }">
                <p class="font-medium">{{ row.message }}</p>
                <p class="text-xs text-muted-foreground md:hidden">
                    {{ formatDateTime(row.logged_at) }}
                </p>
            </template>
            <template #cell-logged_at="{ row }">
                <span class="text-muted-foreground">{{
                    formatDateTime(row.logged_at)
                }}</span>
            </template>
            <template #actions="{ row }">
                <Button variant="outline" size="sm" @click="selected = row">
                    Detail
                </Button>
            </template>
            <template #empty>
                <EmptyState
                    v-if="search || level !== 'all'"
                    :icon="SearchX"
                    title="Tidak ada log yang cocok"
                    description="Ubah kata kunci atau pilih level lain."
                />
                <EmptyState
                    v-else
                    :icon="ScrollText"
                    title="Log masih kosong"
                    description="Belum ada kejadian yang tercatat sejak log terakhir dibersihkan."
                />
            </template>
            <template #footer>
                <TablePagination
                    v-model:page="page"
                    v-model:per-page="perPage"
                    :total="filtered.length"
                    :last-page="lastPage"
                />
            </template>
        </DataTable>
    </div>

    <Sheet v-model:open="detailOpen">
        <SheetContent class="w-full gap-0 sm:max-w-lg">
            <SheetHeader v-if="selected" class="gap-3 border-b border-border">
                <StatusBadge
                    :tone="LEVELS[selected.level].tone"
                    :label="LEVELS[selected.level].label"
                />
                <SheetTitle class="text-base font-semibold">{{
                    selected.message
                }}</SheetTitle>
                <SheetDescription>{{
                    formatDateTime(selected.logged_at)
                }}</SheetDescription>
            </SheetHeader>
            <div class="space-y-2 p-4">
                <p class="text-sm font-medium">Konteks</p>
                <pre
                    class="overflow-x-auto rounded-md bg-muted p-3 text-xs leading-relaxed"
                    >{{ prettyContext }}</pre>
            </div>
        </SheetContent>
    </Sheet>
</template>
