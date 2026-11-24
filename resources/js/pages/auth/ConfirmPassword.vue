<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { Lock, ShieldCheck } from '@lucide/vue';
import {
    index as confirmOptions,
    store as confirmStore,
} from '@/actions/Laravel/Passkeys/Http/Controllers/PasskeyConfirmationController';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useFormValidation } from '@/composables/useFormValidation';
import { confirmPasswordSchema } from '@/lib/schemas';
import { store } from '@/routes/password/confirm';

defineOptions({
    layout: {
        title: 'Konfirmasi kata sandi',
        description:
            'Halaman ini mengubah pengaturan keamanan. Masukkan kata sandi untuk melanjutkan.',
    },
});

// Validation runs here first (zod schema, Indonesian messages) and shows under each field. The
// browser's own bubble is switched off (novalidate). The server stays the final judge: its errors
// render in the same place.
const { shaking, validate, message, aria, clear, hasProblem } =
    useFormValidation(confirmPasswordSchema, ['password'] as const);
</script>

<template>
    <Head title="Konfirmasi kata sandi" />

    <PasskeyVerify
        :routes="{
            options: confirmOptions(),
            submit: confirmStore(),
        }"
        label="Konfirmasi dengan passkey"
        loading-label="Mengonfirmasi..."
        separator="Atau konfirmasi dengan kata sandi"
    />

    <Form
        v-bind="store.form()"
        reset-on-success
        v-slot="{ errors, processing, clearErrors }"
        :class="['flex flex-col gap-6', shaking ? 'shake' : '']"
        novalidate
        :data-invalid="hasProblem || undefined"
        @submit.capture="validate"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="password">Kata sandi</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    :icon="Lock"
                    required
                    autocomplete="current-password"
                    placeholder="Kata sandi"
                    v-bind="aria('password', errors.password)"
                    @input="
                        clear('password');
                        clearErrors('password');
                    "
                />
                <InputError
                    id="password-error"
                    :message="message('password', errors.password)"
                />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                :disabled="processing"
                data-test="confirm-password-button"
            >
                <Spinner v-if="processing" />
                <ShieldCheck v-else aria-hidden="true" />
                {{ processing ? 'Mengonfirmasi...' : 'Konfirmasi' }}
            </Button>
        </div>
    </Form>
</template>
