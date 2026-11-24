<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ListTree } from '@lucide/vue';
import { computed } from 'vue';
import ResourceIndex from '@/components/common/ResourceIndex.vue';
import StatusBadge from '@/components/common/StatusBadge.vue';
import type { Column, Field, FieldValues, Menu } from '@/types';

const props = defineProps<{ rows?: Menu[] }>();

const columns: Column[] = [
    { key: 'name', label: 'Menu' },
    { key: 'route', label: 'Nama route', hideBelow: 'md' },
    { key: 'order', label: 'Urutan', class: 'text-right', hideBelow: 'sm' },
    { key: 'is_active', label: 'Status', hideBelow: 'sm' },
];

const parents = computed(() =>
    (props.rows ?? []).filter((menu) => menu.route === null),
);

const fields = computed<Field[]>(() => [
    {
        name: 'name',
        label: 'Nama menu',
        type: 'text',
        required: true,
        icon: ListTree,
        maxLength: 100,
    },
    {
        name: 'parent_id',
        label: 'Induk',
        type: 'select',
        placeholder: 'Tanpa induk',
        options: [
            { label: 'Tanpa induk', value: 0 },
            ...parents.value.map((menu) => ({
                label: menu.name,
                value: menu.id,
            })),
        ],
    },
    {
        name: 'route',
        label: 'Nama route',
        type: 'text',
        placeholder: 'admin.users.index',
        help: 'Kosongkan untuk menu induk yang hanya mengelompokkan.',
    },
    {
        name: 'icon',
        label: 'Ikon',
        type: 'text',
        placeholder: 'users',
        help: 'Nama ikon Lucide.',
    },
    {
        name: 'order',
        label: 'Urutan',
        type: 'number',
        required: true,
        min: 0,
    },
    { name: 'is_active', label: 'Tampil di sidebar', type: 'switch' },
]);

const toRow = (values: FieldValues): Omit<Menu, 'id'> => {
    const parentId = Number(values.parent_id) || null;

    return {
        name: String(values.name),
        route: values.route ? String(values.route) : null,
        icon: String(values.icon ?? ''),
        parent_id: parentId,
        parent_name:
            parents.value.find((menu) => menu.id === parentId)?.name ?? null,
        order: Number(values.order),
        is_active: Boolean(values.is_active),
    };
};
</script>

<template>
    <Head title="Menu" />

    <ResourceIndex
        title="Menu"
        description="Struktur sidebar admin. Menu induk mengelompokkan, menu anak membuka halaman."
        entity="menu"
        :rows="rows"
        :columns="columns"
        :fields="fields"
        :search-keys="['name', 'route', 'parent_name']"
        :defaults="{
            name: '',
            parent_id: 0,
            route: '',
            icon: '',
            order: 1,
            is_active: true,
        }"
        :row-label="(row) => row.name"
        :to-row="toRow"
        :empty-icon="ListTree"
        empty-description="Menu menentukan isi sidebar. Mulai dari menu induk, lalu tambahkan halaman di bawahnya."
    >
        <template #cell-name="{ row }">
            <div>
                <p
                    class="whitespace-normal"
                    :class="row.parent_id ? 'pl-4' : 'font-medium'"
                >
                    <span v-if="row.parent_id" class="text-muted-foreground"
                        >{{ row.parent_name }} /
                    </span>
                    {{ row.name }}
                </p>
                <StatusBadge
                    class="mt-1.5 sm:hidden"
                    :class="row.parent_id ? 'ml-4' : ''"
                    :tone="row.is_active ? 'success' : 'neutral'"
                    :label="row.is_active ? 'Tampil' : 'Disembunyikan'"
                />
            </div>
        </template>
        <template #cell-route="{ row }">
            <code v-if="row.route" class="text-xs text-muted-foreground">{{
                row.route
            }}</code>
            <span v-else class="text-xs text-muted-foreground">Induk</span>
        </template>
        <template #cell-order="{ row }">
            <span class="tabular-nums">{{ row.order }}</span>
        </template>
        <template #cell-is_active="{ row }">
            <StatusBadge
                :tone="row.is_active ? 'success' : 'neutral'"
                :label="row.is_active ? 'Tampil' : 'Disembunyikan'"
            />
        </template>
    </ResourceIndex>
</template>
