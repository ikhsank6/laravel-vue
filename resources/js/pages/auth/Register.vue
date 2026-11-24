<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { Lock, Mail, User, UserPlus } from '@lucide/vue';
import IconInput from '@/components/common/IconInput.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useFormValidation } from '@/composables/useFormValidation';
import { registerSchema } from '@/lib/schemas';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Buat akun',
        description:
            'Akun baru perlu diberi peran oleh administrator sebelum bisa mengelola konten.',
    },
});

// Validation runs here first (zod schema, Indonesian messages) and shows under each field. The
// browser's own bubble is switched off (novalidate). The server stays the final judge: its errors
// render in the same place.
const { shaking, validate, message, aria, clear, hasProblem } =
    useFormValidation(registerSchema, [
        'name',
        'email',
        'password',
        'password_confirmation',
    ] as const);
</script>

<template>
    <Head title="Daftar" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing, clearErrors }"
        :class="['flex flex-col gap-6', shaking ? 'shake' : '']"
        novalidate
        :data-invalid="hasProblem || undefined"
        @submit.capture="validate"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="name">Nama</Label>
                <IconInput
                    id="name"
                    type="text"
                    name="name"
                    :icon="User"
                    required
                    :tabindex="1"
                    autocomplete="name"
                    placeholder="Nama lengkap"
                    v-bind="aria('name', errors.name)"
                    @input="
                        clear('name');
                        clearErrors('name');
                    "
                />
                <InputError
                    id="name-error"
                    :message="message('name', errors.name)"
                />
            </div>

            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <IconInput
                    id="email"
                    type="email"
                    name="email"
                    :icon="Mail"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    placeholder="nama@lembaga.id"
                    v-bind="aria('email', errors.email)"
                    @input="
                        clear('email');
                        clearErrors('email');
                    "
                />
                <InputError
                    id="email-error"
                    :message="message('email', errors.email)"
                />
            </div>

            <div class="grid gap-2">
                <Label for="password">Kata sandi</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    :icon="Lock"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    :passwordrules="passwordRules"
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

            <div class="grid gap-2">
                <Label for="password_confirmation">Ulangi kata sandi</Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    :icon="Lock"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    placeholder="Ketik ulang kata sandi"
                    :passwordrules="passwordRules"
                    v-bind="
                        aria(
                            'password_confirmation',
                            errors.password_confirmation,
                        )
                    "
                    @input="
                        clear('password_confirmation');
                        clearErrors('password_confirmation');
                    "
                />
                <InputError
                    id="password_confirmation-error"
                    :message="
                        message(
                            'password_confirmation',
                            errors.password_confirmation,
                        )
                    "
                />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                :tabindex="5"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                <UserPlus v-else aria-hidden="true" />
                {{ processing ? 'Membuat akun...' : 'Buat akun' }}
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Sudah punya akun?
            <TextLink :href="login()" :tabindex="6">Masuk</TextLink>
        </div>
    </Form>
</template>
