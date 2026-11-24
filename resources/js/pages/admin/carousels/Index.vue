<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { GalleryHorizontal } from '@lucide/vue';
import CoverArt from '@/components/common/CoverArt.vue';
import ResourceIndex from '@/components/common/ResourceIndex.vue';
import StatusBadge from '@/components/common/StatusBadge.vue';
import type { Carousel, Column, Field, FieldValues } from '@/types';

defineProps<{ rows?: Carousel[] }>();

const columns: Column[] = [
    { key: 'title', label: 'Slide' },
    { key: 'link', label: 'Tautan', hideBelow: 'md' },
    { key: 'order', label: 'Urutan', class: 'text-right', hideBelow: 'sm' },
    { key: 'is_active', label: 'Status', hideBelow: 'sm' },
];

const fields: Field[] = [
    {
        name: 'title',
        label: 'Judul',
        type: 'text',
        required: true,
        wide: true,
        maxLength: 255,
    },
    { name: 'subtitle', label: 'Subjudul', type: 'textarea' },
    { name: 'link', label: 'Tautan tujuan', type: 'url', placeholder: '/news' },
    {
        name: 'link_label',
        label: 'Teks tombol',
        type: 'text',
        placeholder: 'Baca berita terbaru',
        validate: (value, values) =>
            values.link && !value
                ? 'Isi teks tombol karena slide punya tautan.'
                : undefined,
    },
    {
        name: 'order',
        label: 'Urutan',
        type: 'number',
        required: true,
        min: 0,
    },
    { name: 'is_active', label: 'Tampil di beranda', type: 'switch' },
];

const toRow = (values: FieldValues): Omit<Carousel, 'id'> => ({
    title: String(values.title),
    subtitle: String(values.subtitle ?? ''),
    link: values.link ? String(values.link) : null,
    link_label: values.link_label ? String(values.link_label) : null,
    order: Number(values.order),
    is_active: Boolean(values.is_active),
});
</script>

<template>
    <Head title="Slide Beranda" />

    <ResourceIndex
        title="Slide Beranda"
        description="Slide di bagian atas beranda. Hanya slide aktif yang tampil, berurutan dari nomor terkecil."
        entity="slide"
        :rows="rows"
        :columns="columns"
        :fields="fields"
        :search-keys="['title', 'subtitle']"
        :defaults="{
            title: '',
            subtitle: '',
            link: '',
            link_label: '',
            order: 1,
            is_active: true,
        }"
        :row-label="(row) => row.title"
        :to-row="toRow"
        :empty-icon="GalleryHorizontal"
        empty-description="Tanpa slide aktif, beranda langsung dibuka dengan daftar berita terbaru."
    >
        <template #cell-title="{ row }">
            <div class="flex items-center gap-3">
                <CoverArt :title="row.title" size="sm" class="hidden sm:flex" />
                <div class="max-w-md min-w-0">
                    <p
                        class="line-clamp-2 font-medium whitespace-normal sm:line-clamp-1"
                    >
                        {{ row.title }}
                    </p>
                    <p
                        class="line-clamp-1 text-xs whitespace-normal text-muted-foreground"
                    >
                        {{ row.subtitle }}
                    </p>
                    <StatusBadge
                        class="mt-1.5 sm:hidden"
                        :tone="row.is_active ? 'success' : 'neutral'"
                        :label="row.is_active ? 'Tampil' : 'Nonaktif'"
                    />
                </div>
            </div>
        </template>
        <template #cell-link="{ row }">
            <code v-if="row.link" class="text-xs text-muted-foreground">{{
                row.link
            }}</code>
            <span v-else class="text-xs text-muted-foreground"
                >Tanpa tautan</span
            >
        </template>
        <template #cell-order="{ row }">
            <span class="tabular-nums">{{ row.order }}</span>
        </template>
        <template #cell-is_active="{ row }">
            <StatusBadge
                :tone="row.is_active ? 'success' : 'neutral'"
                :label="row.is_active ? 'Tampil' : 'Nonaktif'"
            />
        </template>
    </ResourceIndex>
</template>
