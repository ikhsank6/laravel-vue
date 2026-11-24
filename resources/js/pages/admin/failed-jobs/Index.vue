<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ListChecks, RotateCw, Trash2 } from '@lucide/vue';
import { computed, ref, toRef } from 'vue';
import { toast } from 'vue-sonner';
import ConfirmDialog from '@/components/common/ConfirmDialog.vue';
import DataTable from '@/components/common/DataTable.vue';
import DemoNotice from '@/components/common/DemoNotice.vue';
import EmptyState from '@/components/common/EmptyState.vue';
import PageHeader from '@/components/common/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { useDeferredProp } from '@/composables/useDeferredProp';
import { useLocalCollection } from '@/composables/useLocalCollection';
import { formatDateTime } from '@/lib/format';
import type { Column, FailedJob } from '@/types';

const props = defineProps<{ rows?: FailedJob[] }>();

const source = toRef(props, 'rows');
const { isLoading, isError, retry } = useDeferredProp('rows', source);
const { items, remove } = useLocalCollection(source);

const columns: Column[] = [
    { key: 'job', label: 'Job', class: 'whitespace-normal' },
    { key: 'queue', label: 'Antrian', hideBelow: 'md' },
    { key: 'failed_at', label: 'Gagal pada', hideBelow: 'sm' },
];

const shortName = (job: string) => job.split('\\').pop() ?? job;

function retryJob(job: FailedJob) {
    remove(job.id);
    toast.success(
        `${shortName(job.job)} dikirim ulang ke antrian ${job.queue}.`,
    );
}

const deleting = ref<FailedJob | 'all' | null>(null);
const confirmOpen = computed({
    get: () => deleting.value !== null,
    set: (open) => {
        if (!open) {
            deleting.value = null;
        }
    },
});

function confirmDelete() {
    if (deleting.value === 'all') {
        items.value = [];
        toast.success('Semua job gagal dihapus.');
    } else if (deleting.value) {
        remove(deleting.value.id);
        toast.success('Job gagal dihapus.');
    }

    deleting.value = null;
}
</script>

<template>
    <Head title="Antrian Gagal" />

    <PageHeader
        title="Antrian Gagal"
        description="Job yang gagal dijalankan worker. Perbaiki penyebabnya dulu, lalu kirim ulang."
    >
        <template v-if="items.length > 1" #actions>
            <Button variant="outline" @click="deleting = 'all'"
                >Hapus semua</Button
            >
        </template>
    </PageHeader>

    <div class="space-y-5">
        <DemoNotice />

        <DataTable
            caption="Daftar job gagal"
            :columns="columns"
            :rows="items"
            :loading="isLoading"
            :error="isError"
            @retry="retry"
        >
            <template #header>
                <h2 class="text-base font-semibold">
                    Job gagal
                    <span class="ml-1 text-sm font-normal text-muted-foreground"
                        >({{ items.length }})</span
                    >
                </h2>
            </template>
            <template #cell-job="{ row }">
                <p class="font-medium">{{ shortName(row.job) }}</p>
                <p class="line-clamp-2 text-xs text-destructive">
                    {{ row.exception }}
                </p>
            </template>
            <template #cell-queue="{ row }">
                <code class="text-xs text-muted-foreground">{{
                    row.queue
                }}</code>
            </template>
            <template #cell-failed_at="{ row }">
                <span class="text-muted-foreground">{{
                    formatDateTime(row.failed_at)
                }}</span>
            </template>
            <template #actions="{ row }">
                <Button variant="outline" size="sm" @click="retryJob(row)">
                    <RotateCw class="size-4" />
                    Kirim ulang
                </Button>
                <Button
                    variant="ghost"
                    size="icon-sm"
                    class="hover:text-destructive"
                    :aria-label="`Hapus ${shortName(row.job)}`"
                    @click="deleting = row"
                >
                    <Trash2 class="size-4" />
                </Button>
            </template>
            <template #empty>
                <EmptyState
                    :icon="ListChecks"
                    title="Tidak ada job yang gagal"
                    description="Semua job di antrian berjalan normal. Job yang gagal akan muncul di sini beserta pesan errornya."
                />
            </template>
        </DataTable>
    </div>

    <ConfirmDialog
        v-model:open="confirmOpen"
        :title="
            deleting === 'all' ? 'Hapus semua job gagal?' : 'Hapus job gagal?'
        "
        :description="
            deleting === 'all'
                ? `${items.length} job akan dihapus tanpa dijalankan ulang.`
                : 'Job ini akan dihapus tanpa dijalankan ulang.'
        "
        :confirm-label="deleting === 'all' ? 'Hapus semua' : 'Hapus job'"
        @confirm="confirmDelete"
    />
</template>
