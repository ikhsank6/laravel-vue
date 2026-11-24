<script setup lang="ts" generic="T extends Row">
import { ArrowDown, ArrowUp, ChevronsUpDown } from '@lucide/vue';
import { computed } from 'vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Skeleton } from '@/components/ui/skeleton';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { cn } from '@/lib/utils';
import type { Column, Row } from '@/types';
import ErrorState from './ErrorState.vue';

/**
 * Card-framed table. Layout, top to bottom: header (title), toolbar (search, filters), a
 * bordered table with loading, error and empty states, and a footer (pagination) shown only
 * when rows are available. Sorting and row selection are opt-in.
 */
const props = withDefaults(
    defineProps<{
        columns: Column[];
        rows: T[];
        loading?: boolean;
        error?: boolean;
        caption: string;
        skeletonRows?: number;
        selectable?: boolean;
        sortKey?: string;
        sortDir?: 'asc' | 'desc';
    }>(),
    { loading: false, error: false, skeletonRows: 5, selectable: false },
);

const selected = defineModel<(number | string)[]>('selected', {
    default: () => [],
});

const emit = defineEmits<{ retry: []; sort: [key: string] }>();

defineSlots<
    {
        header?(): unknown;
        toolbar?(): unknown;
        footer?(): unknown;
        empty(): unknown;
        actions?(props: { row: T }): unknown;
    } & Record<`cell-${string}`, (props: { row: T; value: unknown }) => unknown>
>();

const HIDE_BELOW = {
    sm: 'hidden sm:table-cell',
    md: 'hidden md:table-cell',
    lg: 'hidden lg:table-cell',
} as const;

// Phones get tighter horizontal padding, and the action column sticks to the right edge so
// the row menu stays reachable when the table scrolls sideways.
const cellClass = (column: Column) =>
    [
        'max-sm:px-3',
        column.hideBelow ? HIDE_BELOW[column.hideBelow] : '',
        column.class ?? '',
    ].join(' ');

// The box itself stays 16px; on phones an invisible pseudo-element stretches the hit area to 44px.
const checkboxClass =
    "relative max-sm:after:absolute max-sm:after:-inset-3.5 max-sm:after:content-['']";

const pageIds = computed(() => props.rows.map((row) => row.id));
const selectedOnPage = computed(
    () => pageIds.value.filter((id) => selected.value.includes(id)).length,
);

// Header checkbox covers the rows on the current page only.
const allState = computed<boolean | 'indeterminate'>(() =>
    selectedOnPage.value === 0
        ? false
        : selectedOnPage.value === pageIds.value.length
          ? true
          : 'indeterminate',
);

function toggleAll(checked: boolean | 'indeterminate') {
    const rest = selected.value.filter((id) => !pageIds.value.includes(id));

    selected.value = checked === true ? [...rest, ...pageIds.value] : rest;
}

function toggleRow(id: number | string, checked: boolean | 'indeterminate') {
    selected.value =
        checked === true
            ? [...selected.value, id]
            : selected.value.filter((value) => value !== id);
}

const ariaSort = (column: Column) =>
    column.sortable && props.sortKey === column.key
        ? props.sortDir === 'desc'
            ? 'descending'
            : 'ascending'
        : column.sortable
          ? 'none'
          : undefined;
</script>

<template>
    <section class="card-surface overflow-hidden" :aria-label="caption">
        <div
            v-if="$slots.header"
            class="flex flex-col gap-3 border-b border-border px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-5"
        >
            <slot name="header" />
        </div>

        <div
            v-if="$slots.toolbar"
            class="flex flex-col gap-3 px-4 pt-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:pt-5"
        >
            <slot name="toolbar" />
        </div>

        <div class="p-4 sm:p-6" :class="$slots.toolbar ? 'sm:pt-4' : ''">
            <div class="overflow-hidden rounded-md border border-border">
                <ErrorState v-if="error" @retry="emit('retry')" />

                <div v-else-if="loading" aria-busy="true">
                    <span class="sr-only"
                        >Memuat {{ caption.toLowerCase() }}</span
                    >
                    <div
                        v-for="n in skeletonRows"
                        :key="n"
                        class="flex items-center gap-4 border-b border-border px-5 py-3 last:border-b-0"
                    >
                        <Skeleton class="size-9 rounded-full" />
                        <div class="flex-1 space-y-2">
                            <Skeleton class="h-3 w-2/5" />
                            <Skeleton class="h-2.5 w-1/4" />
                        </div>
                        <Skeleton class="hidden h-3 w-24 sm:block" />
                    </div>
                </div>

                <div v-else-if="rows.length === 0">
                    <slot name="empty" />
                </div>

                <div v-else class="overflow-x-auto">
                    <Table>
                        <caption class="sr-only">
                            {{
                                caption
                            }}
                        </caption>
                        <TableHeader>
                            <TableRow class="hover:bg-transparent">
                                <TableHead v-if="selectable" class="w-12 pl-4">
                                    <Checkbox
                                        :class="checkboxClass"
                                        :model-value="allState"
                                        aria-label="Pilih semua baris di halaman ini"
                                        @update:model-value="toggleAll"
                                    />
                                </TableHead>
                                <TableHead
                                    v-for="column in columns"
                                    :key="column.key"
                                    :class="cellClass(column)"
                                    :aria-sort="ariaSort(column)"
                                >
                                    <button
                                        v-if="column.sortable"
                                        type="button"
                                        class="group -mx-2 flex min-h-11 items-center gap-1.5 rounded-sm px-2 text-left hover:text-foreground sm:min-h-9"
                                        @click="emit('sort', column.key)"
                                    >
                                        {{ column.label }}
                                        <ArrowUp
                                            v-if="
                                                sortKey === column.key &&
                                                sortDir === 'asc'
                                            "
                                            class="size-3.5 text-foreground"
                                            aria-hidden="true"
                                        />
                                        <ArrowDown
                                            v-else-if="
                                                sortKey === column.key &&
                                                sortDir === 'desc'
                                            "
                                            class="size-3.5 text-foreground"
                                            aria-hidden="true"
                                        />
                                        <ChevronsUpDown
                                            v-else
                                            class="size-3.5 opacity-60 group-hover:opacity-100"
                                            aria-hidden="true"
                                        />
                                    </button>
                                    <template v-else>{{
                                        column.label
                                    }}</template>
                                </TableHead>
                                <TableHead
                                    v-if="$slots.actions"
                                    class="w-px max-sm:sticky max-sm:right-0 max-sm:border-l max-sm:border-border max-sm:px-2"
                                >
                                    <span class="sr-only">Aksi</span>
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="row in rows"
                                :key="row.id"
                                :data-state="
                                    selected.includes(row.id)
                                        ? 'selected'
                                        : undefined
                                "
                                class="last:border-b-0"
                            >
                                <TableCell v-if="selectable" class="w-12 pl-4">
                                    <Checkbox
                                        :class="checkboxClass"
                                        :model-value="selected.includes(row.id)"
                                        :aria-label="`Pilih baris ${row.id}`"
                                        @update:model-value="
                                            toggleRow(row.id, $event)
                                        "
                                    />
                                </TableCell>
                                <TableCell
                                    v-for="column in columns"
                                    :key="column.key"
                                    :class="cn(cellClass(column))"
                                >
                                    <slot
                                        :name="`cell-${column.key}`"
                                        :row="row"
                                        :value="row[column.key]"
                                    >
                                        {{ row[column.key] }}
                                    </slot>
                                </TableCell>
                                <TableCell
                                    v-if="$slots.actions"
                                    class="text-right max-sm:sticky max-sm:right-0 max-sm:border-l max-sm:border-border max-sm:bg-card max-sm:px-2 max-sm:in-data-[state=selected]:bg-accent"
                                >
                                    <div
                                        class="flex justify-end gap-0.5 max-sm:[&_button]:min-h-11"
                                    >
                                        <slot name="actions" :row="row" />
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>

        <div
            v-if="$slots.footer && !loading && !error && rows.length > 0"
            class="px-4 pb-4 sm:px-6 sm:pb-5"
        >
            <slot name="footer" />
        </div>
    </section>
</template>
