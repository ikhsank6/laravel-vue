<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { SlidersHorizontal } from '@lucide/vue';
import ResourceIndex from '@/components/common/ResourceIndex.vue';
import type { Column, Field, FieldValues, SystemSetting } from '@/types';

defineProps<{ rows?: SystemSetting[] }>();

const columns: Column[] = [
    { key: 'label', label: 'Pengaturan' },
    { key: 'group', label: 'Grup', hideBelow: 'md' },
    { key: 'value', label: 'Nilai' },
];

// Keys are referenced by code, so only the value is editable here.
const fields: Field[] = [
    {
        name: 'label',
        label: 'Pengaturan',
        type: 'text',
        disabledOnEdit: true,
        wide: true,
    },
    {
        name: 'value',
        label: 'Nilai',
        type: 'text',
        required: true,
        wide: true,
        icon: SlidersHorizontal,
    },
];

const toRow = (
    values: FieldValues,
    existing?: SystemSetting,
): Omit<SystemSetting, 'id'> => ({
    key: existing?.key ?? '',
    label: existing?.label ?? String(values.label),
    group: existing?.group ?? '',
    value: String(values.value),
});
</script>

<template>
    <Head title="Pengaturan Sistem" />

    <ResourceIndex
        title="Pengaturan Sistem"
        description="Nilai konfigurasi yang dibaca aplikasi. Kunci dipakai oleh kode, jadi hanya nilainya yang bisa diubah."
        entity="pengaturan"
        :rows="rows"
        :columns="columns"
        :fields="fields"
        :search-keys="['label', 'key', 'group', 'value']"
        :defaults="{}"
        :row-label="(row) => row.label"
        :to-row="toRow"
        :can-create="false"
        :can-delete="false"
        :empty-icon="SlidersHorizontal"
        empty-description="Pengaturan dibuat oleh seeder saat instalasi. Jalankan php artisan db:seed."
    >
        <template #cell-label="{ row }">
            <p class="font-medium">{{ row.label }}</p>
            <code class="text-xs text-muted-foreground">{{ row.key }}</code>
        </template>
    </ResourceIndex>
</template>
