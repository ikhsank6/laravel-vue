<script setup lang="ts">
import { Info } from '@lucide/vue';
</script>

<template>
    <p
        class="flex items-start gap-2 rounded-md border border-info/25 bg-info/5 px-3 py-2 text-sm text-muted-foreground"
    >
        <Info class="mt-0.5 size-4 shrink-0 text-info" aria-hidden="true" />
        <span>
            <slot>
                Data contoh. Perubahan di halaman ini hanya bertahan sampai
                halaman dimuat ulang, belum tersimpan ke database.
            </slot>
        </span>
    </p>
</template>
