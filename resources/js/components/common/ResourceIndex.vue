<script setup lang="ts" generic="T extends Row">
import { EllipsisVertical, Pencil, Plus, SearchX, Trash2 } from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';
import { computed, nextTick, ref, toRef, watch } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useDeferredProp } from '@/composables/useDeferredProp';
import { useLocalCollection } from '@/composables/useLocalCollection';
import { useTableQuery } from '@/composables/useTableQuery';
import { validateFields } from '@/lib/formValidation';
import type { Column, Field, FieldValues, Row } from '@/types';
import ConfirmDialog from './ConfirmDialog.vue';
import DataTable from './DataTable.vue';
import DemoNotice from './DemoNotice.vue';
import EmptyState from './EmptyState.vue';
import FormDialog from './FormDialog.vue';
import FormFields from './FormFields.vue';
import PageHeader from './PageHeader.vue';
import SearchInput from './SearchInput.vue';
import TablePagination from './TablePagination.vue';

/**
 * Index page for one resource: header, search, table, create/edit dialog and delete
 * confirmation. Pages supply columns, fields and optional cell slots; nothing else.
 */
const props = withDefaults(
    defineProps<{
        title: string;
        description?: string;
        /** Singular noun used in labels, e.g. "pengguna". */
        entity: string;
        rows: T[] | undefined;
        columns: Column[];
        fields: Field[];
        searchKeys: string[];
        defaults: FieldValues;
        rowLabel: (row: T) => string;
        /** Turn form values into a full row, e.g. resolve a role name from role_id. */
        toRow?: (values: FieldValues, existing?: T) => Omit<T, 'id'>;
        filter?: (row: T) => boolean;
        emptyIcon: LucideIcon;
        emptyDescription: string;
        canCreate?: boolean;
        canDelete?: boolean;
        deferredKey?: string;
        /** Errors from the server, keyed by field name. Client validation wins on conflict. */
        serverErrors?: Record<string, string>;
    }>(),
    {
        canCreate: true,
        canDelete: true,
        deferredKey: 'rows',
        serverErrors: () => ({}),
    },
);

defineSlots<
    {
        toolbar?(): unknown;
    } & Record<`cell-${string}`, (props: { row: T; value: unknown }) => unknown>
>();

const source = toRef(props, 'rows');
const { isLoading, isError, retry } = useDeferredProp(
    props.deferredKey,
    source,
);
const collection = useLocalCollection(source);

const visible = computed(() =>
    props.filter
        ? collection.items.value.filter(props.filter)
        : collection.items.value,
);
const query = useTableQuery(visible, { searchKeys: props.searchKeys });

// Every column sorts unless a page opts out with `sortable: false`.
const tableColumns = computed(() =>
    props.columns.map((column) => ({ sortable: true, ...column })),
);

// Row selection survives paging and searching; it is pruned when rows disappear.
const selectedIds = ref<(number | string)[]>([]);
const bulkConfirmOpen = ref(false);

watch(
    () => collection.items.value,
    (items) => {
        const ids = new Set(items.map((row) => row.id));

        selectedIds.value = selectedIds.value.filter((id) => ids.has(id));
    },
);

function confirmBulkDelete() {
    const count = selectedIds.value.length;

    selectedIds.value.forEach((id) => collection.remove(id as T['id']));
    selectedIds.value = [];
    toast.success(`${count} ${props.entity} dihapus.`);
}

const cellSlots = computed(() =>
    props.columns.map((column) => `cell-${column.key}` as const),
);

// Create and edit share one dialog; `editing` is null while creating.
const formOpen = ref(false);
const editing = ref<T | null>(null);
const values = ref<FieldValues>({});
const clientErrors = ref<Record<string, string>>({});
const remoteErrors = ref<Record<string, string>>({ ...props.serverErrors });
const shakeKey = ref(0);
const idPrefix = computed(() => props.entity.replace(/\s+/g, '-'));

watch(
    () => props.serverErrors,
    (next) => {
        remoteErrors.value = { ...next };
        focusFirstError();
    },
);

// Client error wins; a server error shows only where the client found none.
const errors = computed(() => ({
    ...remoteErrors.value,
    ...clientErrors.value,
}));

function clearErrors() {
    clientErrors.value = {};
    remoteErrors.value = {};
}

// An error disappears as soon as its field is edited.
function clearError(name: string) {
    if (name in clientErrors.value) {
        const { [name]: _client, ...rest } = clientErrors.value;

        clientErrors.value = rest;
    }

    if (name in remoteErrors.value) {
        const { [name]: _server, ...rest } = remoteErrors.value;

        remoteErrors.value = rest;
    }
}

async function focusFirstError() {
    const name = props.fields.find((field) => errors.value[field.name])?.name;

    if (!name) {
        return;
    }

    shakeKey.value++;
    await nextTick();
    document.getElementById(`${idPrefix.value}-${name}`)?.focus();
}

function openCreate() {
    editing.value = null;
    values.value = { ...props.defaults };
    clearErrors();
    formOpen.value = true;
}

function openEdit(row: T) {
    editing.value = row;
    values.value = { ...row };
    clearErrors();
    formOpen.value = true;
}

function submit() {
    const result = validateFields(props.fields, values.value);

    clientErrors.value = result.errors;

    if (result.firstInvalidName) {
        focusFirstError();

        return;
    }

    const existing = editing.value ?? undefined;
    const row = (props.toRow?.(values.value, existing) ?? values.value) as Omit<
        T,
        'id'
    >;

    if (existing) {
        collection.update(existing.id, row as Partial<T>);
        toast.success(`Perubahan ${props.entity} disimpan.`);
    } else {
        collection.create(row);
        toast.success(`${capitalize(props.entity)} ditambahkan.`);
    }

    formOpen.value = false;
}

const deleting = ref<T | null>(null);
const confirmOpen = computed({
    get: () => deleting.value !== null,
    set: (open) => {
        if (!open) {
            deleting.value = null;
        }
    },
});

function confirmDelete() {
    if (!deleting.value) {
        return;
    }

    collection.remove(deleting.value.id);
    toast.success(`${capitalize(props.entity)} dihapus.`);
    deleting.value = null;
}

function capitalize(value: string) {
    return value.charAt(0).toUpperCase() + value.slice(1);
}

const isFiltered = computed(
    () =>
        query.search.value.trim() !== '' ||
        visible.value.length !== collection.items.value.length,
);
</script>

<template>
    <div>
        <PageHeader :title="title" :description="description" />

        <div class="space-y-5">
            <DemoNotice />

            <DataTable
                :caption="`Daftar ${entity}`"
                v-model:selected="selectedIds"
                :columns="tableColumns"
                :rows="query.paged.value"
                :loading="isLoading"
                :error="isError"
                :selectable="canDelete"
                :sort-key="query.sortKey.value"
                :sort-dir="query.sortDir.value"
                @retry="retry"
                @sort="query.toggleSort"
            >
                <template #header>
                    <h2 class="text-lg font-semibold">
                        Daftar {{ entity }}
                        <span
                            v-if="!isLoading && !isError"
                            class="ml-1 text-sm font-normal text-muted-foreground"
                            >({{ query.filtered.value.length }})</span
                        >
                    </h2>
                    <Button v-if="canCreate" @click="openCreate">
                        <Plus class="size-4" aria-hidden="true" />
                        Tambah {{ entity }}
                    </Button>
                </template>

                <template #toolbar>
                    <div
                        class="flex flex-col gap-2 sm:flex-row sm:items-center"
                    >
                        <slot name="toolbar" />
                        <SearchInput
                            v-model="query.search.value"
                            :label="`Cari ${entity}`"
                            :placeholder="`Cari ${entity}...`"
                        />
                    </div>
                    <div class="flex items-center gap-2 empty:max-sm:hidden sm:min-h-9">
                        <template v-if="selectedIds.length > 0">
                            <span
                                class="text-sm text-muted-foreground"
                                aria-live="polite"
                                >{{ selectedIds.length }} dipilih</span
                            >
                            <Button
                                variant="outline"
                                size="sm"
                                class="hover:text-destructive"
                                @click="bulkConfirmOpen = true"
                            >
                                <Trash2 class="size-4" aria-hidden="true" />
                                Hapus yang dipilih
                            </Button>
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="selectedIds = []"
                            >
                                Batal pilih
                            </Button>
                        </template>
                    </div>
                </template>

                <template v-for="slot in cellSlots" :key="slot" #[slot]="scope">
                    <slot :name="slot" v-bind="scope">{{ scope.value }}</slot>
                </template>

                <template #actions="{ row }">
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button
                                variant="ghost"
                                size="icon-sm"
                                class="size-11 sm:size-9"
                                :aria-label="`Aksi untuk ${rowLabel(row)}`"
                            >
                                <EllipsisVertical
                                    class="size-4"
                                    aria-hidden="true"
                                />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuItem
                                class="min-h-11 sm:min-h-8"
                                @select="openEdit(row)"
                            >
                                <Pencil class="size-4" aria-hidden="true" />
                                Ubah
                            </DropdownMenuItem>
                            <template v-if="canDelete">
                                <DropdownMenuSeparator />
                                <DropdownMenuItem
                                    variant="destructive"
                                    class="min-h-11 sm:min-h-8"
                                    @select="deleting = row"
                                >
                                    <Trash2 class="size-4" aria-hidden="true" />
                                    Hapus
                                </DropdownMenuItem>
                            </template>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </template>

                <template #empty>
                    <EmptyState
                        v-if="isFiltered"
                        :icon="SearchX"
                        :title="`Tidak ada ${entity} yang cocok`"
                        description="Ubah kata kunci atau filter untuk melihat data lain."
                    >
                        <Button
                            v-if="query.search.value"
                            variant="outline"
                            size="sm"
                            @click="query.search.value = ''"
                        >
                            Hapus pencarian
                        </Button>
                    </EmptyState>
                    <EmptyState
                        v-else
                        :icon="emptyIcon"
                        :title="`Belum ada ${entity}`"
                        :description="emptyDescription"
                    >
                        <Button
                            v-if="canCreate"
                            variant="outline"
                            size="sm"
                            @click="openCreate"
                        >
                            <Plus class="size-4" aria-hidden="true" />
                            Tambah {{ entity }} pertama
                        </Button>
                    </EmptyState>
                </template>

                <template #footer>
                    <TablePagination
                        v-model:page="query.page.value"
                        v-model:per-page="query.perPage.value"
                        :total="query.filtered.value.length"
                        :last-page="query.lastPage.value"
                    />
                </template>
            </DataTable>
        </div>

        <FormDialog
            v-model:open="formOpen"
            :title="editing ? `Ubah ${entity}` : `Tambah ${entity}`"
            :submit-label="editing ? 'Simpan perubahan' : `Tambah ${entity}`"
            :shake-key="shakeKey"
            @submit="submit"
        >
            <FormFields
                v-model="values"
                :fields="fields"
                :errors="errors"
                :is-edit="!!editing"
                :id-prefix="idPrefix"
                @change="clearError"
            />
        </FormDialog>

        <ConfirmDialog
            v-model:open="confirmOpen"
            :title="`Hapus ${entity}?`"
            :description="
                deleting
                    ? `${rowLabel(deleting)} akan dihapus dari daftar. Tindakan ini tidak bisa dibatalkan.`
                    : ''
            "
            :confirm-label="`Hapus ${entity}`"
            @confirm="confirmDelete"
        />

        <ConfirmDialog
            v-model:open="bulkConfirmOpen"
            :title="`Hapus ${selectedIds.length} ${entity}?`"
            :description="`${selectedIds.length} ${entity} yang dipilih akan dihapus dari daftar. Tindakan ini tidak bisa dibatalkan.`"
            :confirm-label="`Hapus ${selectedIds.length} ${entity}`"
            @confirm="confirmBulkDelete"
        />
    </div>
</template>
