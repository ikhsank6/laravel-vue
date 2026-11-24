<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Shield, ShieldCheck } from '@lucide/vue';
import ResourceIndex from '@/components/common/ResourceIndex.vue';
import { slugify } from '@/lib/format';
import type { Column, Field, FieldValues, Role } from '@/types';

defineProps<{ rows?: Role[] }>();

const columns: Column[] = [
    { key: 'name', label: 'Peran' },
    {
        key: 'description',
        label: 'Wewenang',
        hideBelow: 'md',
        class: 'whitespace-normal',
    },
    { key: 'users_count', label: 'Pengguna', class: 'text-right' },
];

const fields: Field[] = [
    {
        name: 'name',
        label: 'Nama peran',
        type: 'text',
        required: true,
        placeholder: 'Contoh: Editor',
        wide: true,
        icon: ShieldCheck,
        maxLength: 100,
    },
    {
        name: 'description',
        label: 'Wewenang',
        type: 'textarea',
        placeholder: 'Apa saja yang boleh dilakukan peran ini',
    },
];

const toRow = (values: FieldValues, existing?: Role): Omit<Role, 'id'> => ({
    name: String(values.name),
    slug: slugify(String(values.name)),
    description: String(values.description ?? ''),
    users_count: existing?.users_count ?? 0,
});
</script>

<template>
    <Head title="Peran" />

    <ResourceIndex
        title="Peran"
        description="Kelompok wewenang. Atur menu yang boleh dibuka tiap peran di Akses Menu."
        entity="peran"
        :rows="rows"
        :columns="columns"
        :fields="fields"
        :search-keys="['name', 'description']"
        :defaults="{ name: '', description: '' }"
        :row-label="(row) => row.name"
        :to-row="toRow"
        :empty-icon="Shield"
        empty-description="Buat peran sebelum menambahkan pengguna, karena tiap akun wajib punya satu peran."
    >
        <template #cell-name="{ row }">
            <p class="font-medium">{{ row.name }}</p>
            <p class="text-xs text-muted-foreground">{{ row.slug }}</p>
        </template>
        <template #cell-users_count="{ row }">
            <span class="tabular-nums">{{ row.users_count }}</span>
        </template>
    </ResourceIndex>
</template>
