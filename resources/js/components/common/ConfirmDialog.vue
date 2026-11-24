<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button, buttonVariants } from '@/components/ui/button';

const open = defineModel<boolean>('open', { default: false });

withDefaults(
    defineProps<{
        title: string;
        description: string;
        confirmLabel?: string;
        destructive?: boolean;
    }>(),
    { confirmLabel: 'Hapus', destructive: true },
);

const emit = defineEmits<{ confirm: [] }>();

// Emit before closing: closing first would let parents clear the item being confirmed.
function confirm() {
    emit('confirm');
    open.value = false;
}
</script>

<template>
    <AlertDialog v-model:open="open">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ title }}</AlertDialogTitle>
                <AlertDialogDescription>{{
                    description
                }}</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel
                    :class="buttonVariants({ variant: 'ghost' })"
                >
                    Batal
                </AlertDialogCancel>
                <Button
                    :variant="destructive ? 'destructive' : 'default'"
                    @click="confirm"
                >
                    {{ confirmLabel }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
