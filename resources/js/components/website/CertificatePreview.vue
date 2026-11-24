<script setup lang="ts">
import { computed } from 'vue';

// An illustration of the document this institution issues. Names and numbers are
// placeholders on purpose and the caption says so. It is a paper object, so it keeps
// paper colors in both themes. Units tick in one by one, then the seal is stamped, once.
const units = [
    { label: 'Unit kompetensi 1', delay: 900 },
    { label: 'Unit kompetensi 2', delay: 1200 },
    { label: 'Unit kompetensi 3', delay: 1500 },
];

// Scalloped rosette: 28 points alternating between two radii.
const sealPath = computed(() => {
    const points = Array.from({ length: 56 }, (_, i) => {
        const r = i % 2 === 0 ? 48 : 43;
        const a = (Math.PI * 2 * i) / 56 - Math.PI / 2;

        return `${(50 + r * Math.cos(a)).toFixed(2)},${(50 + r * Math.sin(a)).toFixed(2)}`;
    });

    return `M${points.join('L')}Z`;
});
</script>

<template>
    <figure class="mx-auto w-full max-w-md">
        <div
            class="relative rounded-md bg-white p-2 text-[#0f2a4a] shadow-[0_24px_48px_-24px_rgb(0_0_0/0.6)]"
        >
            <div class="relative rounded-sm border border-[#0f2a4a]/25 p-1">
                <div
                    class="rounded-xs border border-[#f2b01e]/70 px-6 pt-5 pb-8 sm:px-8"
                >
                    <div class="rule-guilloche mx-auto w-24 text-[#f2b01e]" />
                    <p
                        class="mt-4 text-center font-display text-2xl font-semibold tracking-tight sm:text-[28px]"
                    >
                        Sertifikat Kompetensi
                    </p>

                    <p class="mt-5 text-center text-xs text-[#4a5f78]">
                        Diberikan kepada
                    </p>
                    <p
                        class="mx-auto mt-1 max-w-[16rem] border-b border-dashed border-[#0f2a4a]/40 pb-1 text-center font-display text-xl text-[#4a5f78] italic"
                    >
                        Nama peserta
                    </p>

                    <p class="mt-5 text-center text-xs text-[#4a5f78]">
                        atas skema
                    </p>
                    <p class="mt-1 text-center font-semibold">
                        Nama skema sertifikasi
                    </p>

                    <ul class="mx-auto mt-5 max-w-xs space-y-2 text-sm">
                        <li
                            v-for="unit in units"
                            :key="unit.label"
                            class="flex items-center gap-2.5"
                        >
                            <svg
                                viewBox="0 0 20 20"
                                class="size-5 shrink-0"
                                aria-hidden="true"
                            >
                                <circle
                                    cx="10"
                                    cy="10"
                                    r="9"
                                    fill="none"
                                    stroke="#2563a8"
                                    stroke-width="1.5"
                                />
                                <path
                                    class="cert-tick"
                                    :style="{ '--d': `${unit.delay}ms` }"
                                    d="M5.5 10.5l3 3 6-7"
                                    fill="none"
                                    stroke="#2563a8"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <span class="flex-1">{{ unit.label }}</span>
                            <span class="text-xs text-[#4a5f78]">Kompeten</span>
                        </li>
                    </ul>
                </div>
            </div>

            <svg
                viewBox="0 0 100 100"
                class="cert-seal absolute -right-6 -bottom-10 size-24 text-[#0f2a4a]"
                style="--d: 2100ms"
                role="img"
                aria-label="Cap Kompeten"
            >
                <path :d="sealPath" fill="#f2b01e" />
                <circle
                    cx="50"
                    cy="50"
                    r="35"
                    fill="none"
                    stroke="#0f2a4a"
                    stroke-width="1.4"
                />
                <circle
                    cx="50"
                    cy="50"
                    r="31"
                    fill="none"
                    stroke="#0f2a4a"
                    stroke-width="0.6"
                />
                <path
                    d="M34 48l11 11 21-24"
                    fill="none"
                    stroke="#0f2a4a"
                    stroke-width="5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <text
                    x="50"
                    y="76"
                    text-anchor="middle"
                    font-size="9"
                    font-weight="700"
                    fill="#0f2a4a"
                >
                    Kompeten
                </text>
            </svg>
        </div>
        <figcaption class="mt-6 text-center text-xs text-muted-foreground">
            Ilustrasi sertifikat. Bukan dokumen resmi.
        </figcaption>
    </figure>
</template>
