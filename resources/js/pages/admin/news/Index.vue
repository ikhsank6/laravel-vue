<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Newspaper } from '@lucide/vue';
import { computed, ref } from 'vue';
import CoverArt from '@/components/common/CoverArt.vue';
import ResourceIndex from '@/components/common/ResourceIndex.vue';
import StatusBadge from '@/components/common/StatusBadge.vue';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { formatDate, slugify } from '@/lib/format';
import type {
    Column,
    Field,
    FieldValues,
    News,
    NewsCategory,
    NewsStatus,
} from '@/types';

const props = defineProps<{
    rows?: News[];
    categories: NewsCategory[];
}>();

const status = ref<'all' | NewsStatus>('all');
const filter = computed(() =>
    status.value === 'all'
        ? undefined
        : (row: News) => row.status === status.value,
);

const columns: Column[] = [
    { key: 'title', label: 'Judul' },
    { key: 'category_name', label: 'Kategori', hideBelow: 'md' },
    { key: 'status', label: 'Status', hideBelow: 'sm' },
    { key: 'published_at', label: 'Terbit', hideBelow: 'sm' },
];

const fields = computed<Field[]>(() => [
    {
        name: 'title',
        label: 'Judul',
        type: 'text',
        required: true,
        wide: true,
        maxLength: 255,
    },
    {
        name: 'category_id',
        label: 'Kategori',
        type: 'select',
        required: true,
        options: props.categories.map((c) => ({ label: c.name, value: c.id })),
    },
    {
        name: 'status',
        label: 'Status',
        type: 'select',
        required: true,
        options: [
            { label: 'Draf', value: 'draft' },
            { label: 'Terbit', value: 'published' },
        ],
    },
    {
        name: 'excerpt',
        label: 'Ringkasan',
        type: 'textarea',
        required: true,
        help: 'Satu sampai dua kalimat. Tampil di kartu berita dan pratinjau tautan.',
    },
    { name: 'content', label: 'Isi berita', type: 'textarea', required: true },
]);

const toRow = (values: FieldValues, existing?: News): Omit<News, 'id'> => {
    const isPublished = values.status === 'published';
    const now = new Date().toISOString();

    return {
        title: String(values.title),
        slug: slugify(String(values.title)),
        category_id: Number(values.category_id),
        category_name:
            props.categories.find((c) => c.id === Number(values.category_id))
                ?.name ?? '',
        status: values.status as NewsStatus,
        published_at: isPublished ? (existing?.published_at ?? now) : null,
        excerpt: String(values.excerpt),
        content: String(values.content),
        updated_at: now,
    };
};
</script>

<template>
    <Head title="Berita" />

    <ResourceIndex
        title="Berita"
        description="Tulis sebagai draf, terbitkan setelah ditinjau. Berita terbit langsung tampil di website."
        entity="berita"
        :rows="rows"
        :columns="columns"
        :fields="fields"
        :filter="filter"
        :search-keys="['title', 'excerpt', 'category_name']"
        :defaults="{
            title: '',
            category_id: categories[0]?.id,
            status: 'draft',
            excerpt: '',
            content: '',
        }"
        :row-label="(row) => row.title"
        :to-row="toRow"
        :empty-icon="Newspaper"
        empty-description="Berita yang terbit tampil di halaman Berita dan beranda website."
    >
        <template #toolbar>
            <Tabs v-model="status">
                <TabsList aria-label="Saring status berita">
                    <TabsTrigger value="all">Semua</TabsTrigger>
                    <TabsTrigger value="draft">Draf</TabsTrigger>
                    <TabsTrigger value="published">Terbit</TabsTrigger>
                </TabsList>
            </Tabs>
        </template>
        <template #cell-title="{ row }">
            <div class="flex items-center gap-3">
                <CoverArt :title="row.title" size="sm" class="hidden sm:flex" />
                <div class="min-w-0">
                    <p
                        class="line-clamp-2 max-w-xs font-medium whitespace-normal sm:line-clamp-1 sm:max-w-md"
                    >
                        {{ row.title }}
                    </p>
                    <StatusBadge
                        class="mt-1.5 sm:hidden"
                        :tone="
                            row.status === 'published' ? 'success' : 'warning'
                        "
                        :label="row.status === 'published' ? 'Terbit' : 'Draf'"
                    />
                </div>
            </div>
        </template>
        <template #cell-status="{ row }">
            <StatusBadge
                :tone="row.status === 'published' ? 'success' : 'warning'"
                :label="row.status === 'published' ? 'Terbit' : 'Draf'"
            />
        </template>
        <template #cell-published_at="{ row }">
            <span class="text-muted-foreground">{{
                formatDate(row.published_at) || 'Belum terbit'
            }}</span>
        </template>
    </ResourceIndex>
</template>
