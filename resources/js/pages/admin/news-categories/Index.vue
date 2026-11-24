<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Tag, Tags } from '@lucide/vue';
import ResourceIndex from '@/components/common/ResourceIndex.vue';
import { slugify } from '@/lib/format';
import type { Column, Field, FieldValues, NewsCategory } from '@/types';

defineProps<{ rows?: NewsCategory[] }>();

const columns: Column[] = [
    { key: 'name', label: 'Kategori' },
    {
        key: 'description',
        label: 'Deskripsi',
        hideBelow: 'md',
        class: 'whitespace-normal',
    },
];

const fields: Field[] = [
    {
        name: 'name',
        label: 'Nama kategori',
        type: 'text',
        required: true,
        wide: true,
        icon: Tag,
        maxLength: 255,
        help: 'Slug untuk alamat halaman dibuat otomatis dari nama.',
    },
    { name: 'description', label: 'Deskripsi', type: 'textarea' },
];

const toRow = (values: FieldValues): Omit<NewsCategory, 'id'> => ({
    name: String(values.name),
    slug: slugify(String(values.name)),
    description: String(values.description ?? ''),
});
</script>

<template>
    <Head title="Kategori Berita" />

    <ResourceIndex
        title="Kategori Berita"
        description="Pengelompokan berita. Pengunjung bisa menyaring berita per kategori di website."
        entity="kategori"
        :rows="rows"
        :columns="columns"
        :fields="fields"
        :search-keys="['name', 'description']"
        :defaults="{ name: '', description: '' }"
        :row-label="(row) => row.name"
        :to-row="toRow"
        :empty-icon="Tags"
        empty-description="Setiap berita wajib punya kategori, jadi buat minimal satu sebelum menulis."
    >
        <template #cell-name="{ row }">
            <p class="font-medium">{{ row.name }}</p>
            <p class="text-xs text-muted-foreground">
                /news?category={{ row.slug }}
            </p>
        </template>
    </ResourceIndex>
</template>
