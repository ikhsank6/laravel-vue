<script setup lang="ts">
import { TriangleAlert } from '@lucide/vue';
import { Button } from '@/components/ui/button';

withDefaults(
    defineProps<{
        title?: string;
        description?: string;
    }>(),
    {
        title: 'Data gagal dimuat',
        description:
            'Server tidak mengembalikan data. Coba muat ulang; jika tetap gagal, periksa menu Log.',
    },
);

defineEmits<{ retry: [] }>();
</script>

<template>
    <div
        role="alert"
        class="flex flex-col items-center gap-3 px-6 py-14 text-center"
    >
        <TriangleAlert class="size-8 text-destructive" aria-hidden="true" />
        <div class="space-y-1">
            <p class="font-semibold">{{ title }}</p>
            <p class="mx-auto max-w-sm text-sm text-muted-foreground">
                {{ description }}
            </p>
        </div>
        <Button variant="outline" size="sm" @click="$emit('retry')">
            Muat ulang
        </Button>
    </div>
</template>
