<script setup lang="ts">
import { LogIn, Lock, Mail } from '@lucide/vue';
import { Form, Head } from '@inertiajs/vue3';
import IconInput from '@/components/common/IconInput.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useFormValidation } from '@/composables/useFormValidation';
import { loginSchema } from '@/lib/schemas';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Masuk ke panel admin',
        description: 'Pakai email dan kata sandi akun yang sudah terdaftar.',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

// Validation runs here first (zod schema, Indonesian messages) and shows under each field. The
// browser's own bubble is switched off (novalidate). The server stays the final judge: its errors
// render in the same place.
const { shaking, validate, message, aria, clear, hasProblem } =
    useFormValidation(loginSchema, ['email', 'password'] as const);
</script>

<template>
    <Head title="Masuk" />

    <div
        v-if="status"
        class="mb-4 rounded-md border border-success/30 bg-success/10 px-3 py-2 text-sm font-semibold text-success"
        role="status"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing, clearErrors }"
        :class="['flex flex-col gap-6', shaking ? 'shake' : '']"
        novalidate
        :data-invalid="hasProblem || undefined"
        @submit.capture="validate"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <IconInput
                    id="email"
                    type="email"
                    name="email"
                    :icon="Mail"
                    required
                    :tabindex="1"
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
                <div class="flex items-center justify-between">
                    <Label for="password">Kata sandi</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-sm"
                        :tabindex="5"
                    >
                        Lupa kata sandi?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    :icon="Lock"
                    required
                    :tabindex="2"
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

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span>Ingat saya di perangkat ini</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                <LogIn v-else aria-hidden="true" />
                {{ processing ? 'Memeriksa...' : 'Masuk' }}
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Belum punya akun?
            <TextLink :href="register()" :tabindex="5">Daftar</TextLink>
        </div>
    </Form>
</template>
