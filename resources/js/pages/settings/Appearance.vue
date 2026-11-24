<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import { edit } from '@/routes/appearance';
</script>

<template>
    <Head title="Tampilan" />

    <h1 class="sr-only">Pengaturan tampilan</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Tampilan"
            description="Pilih tema terang, gelap, atau ikuti pengaturan perangkat. Pilihan disimpan terpisah untuk website dan panel admin."
        />
        <AppearanceTabs />
    </div>
</template>
