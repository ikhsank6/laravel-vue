<script setup lang="ts">
import { Save, X } from '@lucide/vue';
import { nextTick, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

const open = defineModel<boolean>('open', { default: false });

const props = withDefaults(
    defineProps<{
        title: string;
        description?: string;
        submitLabel?: string;
        /** Bump this number to shake the form once, e.g. after a failed validation. */
        shakeKey?: number;
    }>(),
    { submitLabel: 'Simpan', shakeKey: 0 },
);

// The shake animates the form, not the dialog box: the box is centred with a transform.
// The class is dropped and re-added so the same animation can play again.
const shaking = ref(false);

watch(
    () => props.shakeKey,
    async () => {
        shaking.value = false;
        await nextTick();
        void document.body.offsetWidth;
        shaking.value = true;
    },
);

defineEmits<{ submit: [] }>();
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="max-h-[90dvh] overflow-y-auto sm:max-w-xl">
            <form
                :class="['grid gap-6', shaking && 'shake']"
                novalidate
                @animationend.self="shaking = false"
                @submit.prevent="$emit('submit')"
            >
                <DialogHeader>
                    <DialogTitle class="text-lg font-semibold">{{
                        title
                    }}</DialogTitle>
                    <DialogDescription v-if="description">
                        {{ description }}
                    </DialogDescription>
                </DialogHeader>

                <slot />

                <DialogFooter class="gap-2">
                    <Button type="button" variant="ghost" @click="open = false">
                        <X aria-hidden="true" />
                        Batal
                    </Button>
                    <Button type="submit">
                        <Save aria-hidden="true" />
                        {{ submitLabel }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
