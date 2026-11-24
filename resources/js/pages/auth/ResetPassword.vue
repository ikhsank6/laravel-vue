<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { KeyRound, Lock, Mail } from '@lucide/vue';
import { ref } from 'vue';
import IconInput from '@/components/common/IconInput.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useFormValidation } from '@/composables/useFormValidation';
import { resetPasswordSchema } from '@/lib/schemas';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Buat kata sandi baru',
        description: 'Kata sandi lama tidak berlaku setelah ini disimpan.',
    },
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const inputEmail = ref(props.email);

// Validation runs here first (zod schema, Indonesian messages) and shows under each field. The
// browser's own bubble is switched off (novalidate). The server stays the final judge: its errors
// render in the same place. The email is read-only and only shows a server error (for example an
// expired link), so it is not part of the schema.
const { shaking, validate, message, aria, clear, hasProblem } =
    useFormValidation(resetPasswordSchema, [
        'password',
        'password_confirmation',
    ] as const);
</script>

<template>
    <Head title="Buat kata sandi baru" />

    <Form
        v-bind="update.form()"
        :transform="(data) => ({ ...data, token, email })"
        :reset-on-success="['password', 'password_confirmation']"
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
                    v-model="inputEmail"
                    :icon="Mail"
                    autocomplete="email"
                    readonly
                    :invalid="!!errors.email"
                    :aria-invalid="!!errors.email"
                    :aria-describedby="errors.email ? 'email-error' : undefined"
                />
                <InputError id="email-error" :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Kata sandi baru</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    :icon="Lock"
                    required
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
                :disabled="processing"
                data-test="reset-password-button"
            >
                <Spinner v-if="processing" />
                <KeyRound v-else aria-hidden="true" />
                {{ processing ? 'Menyimpan...' : 'Simpan kata sandi' }}
            </Button>
        </div>
    </Form>
</template>
