<script setup lang="ts">
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { cn } from '@/lib/utils';

const page = defineModel<number>('page', { required: true });
const perPage = defineModel<number>('perPage', { required: true });

const props = withDefaults(
    defineProps<{
        total: number;
        lastPage: number;
        pageSizes?: number[];
    }>(),
    { pageSizes: () => [5, 10, 25, 50] },
);

const range = computed(() => {
    if (props.total === 0) {
        return '0 data';
    }

    const from = (page.value - 1) * perPage.value + 1;
    const to = Math.min(page.value * perPage.value, props.total);

    return `${from}-${to} dari ${props.total}`;
});

// Page numbers with gaps: first, last, and two around the current page.
const pages = computed<(number | 'gap')[]>(() => {
    const last = props.lastPage;
    const keep = new Set([1, last, page.value - 1, page.value, page.value + 1]);
    const result: (number | 'gap')[] = [];

    for (let n = 1; n <= last; n++) {
        if (keep.has(n)) {
            result.push(n);
        } else if (result[result.length - 1] !== 'gap') {
            result.push('gap');
        }
    }

    return result;
});

// Jump to page: clamps to the valid range, so any typed number lands somewhere real.
const jump = ref(String(page.value));

watch(page, (value) => (jump.value = String(value)));

function goToPage() {
    const target = Math.trunc(Number(jump.value));

    if (Number.isNaN(target)) {
        jump.value = String(page.value);

        return;
    }

    page.value = Math.min(Math.max(target, 1), props.lastPage);
    jump.value = String(page.value);
}

const buttonClass = (active = false) =>
    cn(
        'flex size-11 items-center justify-center rounded-md text-sm transition-colors disabled:pointer-events-none disabled:opacity-40 sm:size-8',
        active
            ? 'bg-accent font-semibold text-foreground'
            : 'text-muted-foreground hover:bg-accent hover:text-foreground',
    );
</script>

<template>
    <div
        class="flex flex-col gap-3 text-sm text-muted-foreground lg:flex-row lg:items-center lg:justify-between"
    >
        <div class="flex items-center gap-2">
            <span id="page-size-label">Tampilkan</span>
            <Select
                :model-value="perPage"
                @update:model-value="perPage = Number($event)"
            >
                <SelectTrigger
                    class="w-[4.5rem] max-sm:data-[size=sm]:h-11"
                    size="sm"
                    aria-labelledby="page-size-label"
                >
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="size in pageSizes"
                        :key="size"
                        :value="size"
                    >
                        {{ size }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <span>per halaman</span>
        </div>

        <nav
            class="flex items-center gap-3"
            aria-label="Navigasi halaman tabel"
        >
            <span aria-live="polite">{{ range }}</span>
            <div class="flex items-center gap-0.5">
                <button
                    type="button"
                    :class="buttonClass()"
                    aria-label="Halaman sebelumnya"
                    :disabled="page <= 1"
                    @click="page--"
                >
                    <ChevronLeft class="size-4" />
                </button>
                <template v-for="(item, i) in pages" :key="`${item}-${i}`">
                    <span v-if="item === 'gap'" class="px-1" aria-hidden="true"
                        >...</span
                    >
                    <button
                        v-else
                        type="button"
                        :class="buttonClass(item === page)"
                        :aria-label="`Halaman ${item}`"
                        :aria-current="item === page ? 'page' : undefined"
                        @click="page = item"
                    >
                        {{ item }}
                    </button>
                </template>
                <button
                    type="button"
                    :class="buttonClass()"
                    aria-label="Halaman berikutnya"
                    :disabled="page >= lastPage"
                    @click="page++"
                >
                    <ChevronRight class="size-4" />
                </button>
            </div>
        </nav>

        <form
            v-if="lastPage > 1"
            class="flex items-center gap-2"
            @submit.prevent="goToPage"
        >
            <label for="jump-to-page" class="whitespace-nowrap"
                >Ke halaman</label
            >
            <Input
                id="jump-to-page"
                v-model="jump"
                type="number"
                inputmode="numeric"
                :min="1"
                :max="lastPage"
                class="h-11 w-16 px-2 text-center sm:h-8 sm:min-h-8"
                :aria-label="`Ke halaman, 1 sampai ${lastPage}`"
                @blur="goToPage"
            />
            <Button
                type="submit"
                variant="outline"
                size="sm"
                class="max-sm:min-h-11"
                >Pergi</Button
            >
        </form>
    </div>
</template>
