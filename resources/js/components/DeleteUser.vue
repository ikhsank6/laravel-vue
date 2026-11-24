<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Lock, Trash2 } from '@lucide/vue';
import { reactive, ref, useTemplateRef } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { firstErrors } from '@/lib/schemas';
import { deleteAccountSchema } from '@/lib/settingsSchemas';

const passwordInput = useTemplateRef('passwordInput');

// Validation runs here first (zod schema, Indonesian message) and shows under the field. The
// browser's own bubble is switched off (novalidate). The server stays the final judge: its
// errors (wrong password) render in the same place.
const clientErrors = reactive<{ password?: string }>({});
const shaking = ref(false);

const validate = (event: Event) => {
    const form = event.target as HTMLFormElement;
    const data = new FormData(form);
    const result = deleteAccountSchema.safeParse({
        password: data.get('password') ?? '',
    });

    clientErrors.password = undefined;

    if (result.success) {
        return;
    }

    Object.assign(clientErrors, firstErrors<'password'>(result.error));

    event.preventDefault();
    event.stopImmediatePropagation();
    passwordInput.value?.focus();

    shaking.value = false;
    requestAnimationFrame(() => {
        shaking.value = true;
        setTimeout(() => (shaking.value = false), 400);
    });
};

const passwordMessage = (server?: string) => clientErrors.password ?? server;
</script>

<template>
    <div class="space-y-6">
        <Heading
            variant="small"
            title="Hapus akun"
            description="Hapus akun Anda beserta semua data di dalamnya."
        />
        <div
            class="space-y-4 rounded-lg border border-destructive/30 bg-destructive/10 p-4"
        >
            <div class="relative space-y-0.5 text-destructive">
                <p class="font-medium">Peringatan</p>
                <p class="text-sm">Penghapusan akun tidak dapat dibatalkan.</p>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button
                        variant="destructive"
                        data-test="delete-user-button"
                    >
                        <Trash2 class="size-4" aria-hidden="true" />
                        Hapus akun
                    </Button>
                </DialogTrigger>
                <DialogContent>
                    <Form
                        v-bind="ProfileController.destroy.form()"
                        reset-on-success
                        @error="() => passwordInput?.focus()"
                        :options="{
                            preserveScroll: true,
                        }"
                        :class="['space-y-6', shaking ? 'shake' : '']"
                        novalidate
                        v-slot="{ errors, processing, reset, clearErrors }"
                        @submit.capture="validate"
                    >
                        <DialogHeader class="space-y-3">
                            <DialogTitle
                                >Hapus akun Anda secara permanen?</DialogTitle
                            >
                            <DialogDescription>
                                Setelah akun dihapus, semua data di dalamnya
                                ikut terhapus permanen. Masukkan kata sandi Anda
                                untuk mengonfirmasi penghapusan.
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid gap-2">
                            <Label for="password" class="sr-only"
                                >Kata sandi</Label
                            >
                            <div class="relative">
                                <Lock
                                    :class="[
                                        'pointer-events-none absolute top-1/2 left-3 z-10 size-4 -translate-y-1/2',
                                        passwordMessage(errors.password)
                                            ? 'text-destructive'
                                            : 'text-muted-foreground',
                                    ]"
                                    aria-hidden="true"
                                />
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    ref="passwordInput"
                                    class="pl-9"
                                    autocomplete="current-password"
                                    placeholder="Kata sandi"
                                    :aria-invalid="
                                        !!passwordMessage(errors.password)
                                    "
                                    :aria-describedby="
                                        passwordMessage(errors.password)
                                            ? 'delete-password-error'
                                            : undefined
                                    "
                                    @input="
                                        clientErrors.password = undefined;
                                        clearErrors('password');
                                    "
                                />
                            </div>
                            <InputError
                                id="delete-password-error"
                                :message="passwordMessage(errors.password)"
                            />
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button
                                    variant="secondary"
                                    @click="
                                        () => {
                                            clientErrors.password = undefined;
                                            clearErrors();
                                            reset();
                                        }
                                    "
                                >
                                    Batal
                                </Button>
                            </DialogClose>

                            <Button
                                type="submit"
                                variant="destructive"
                                :disabled="processing"
                                data-test="confirm-delete-user-button"
                            >
                                <Spinner v-if="processing" />
                                <Trash2
                                    v-else
                                    class="size-4"
                                    aria-hidden="true"
                                />
                                {{ processing ? 'Menghapus...' : 'Hapus akun' }}
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
