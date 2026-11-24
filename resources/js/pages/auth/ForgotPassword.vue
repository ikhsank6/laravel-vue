<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { Mail, Send } from '@lucide/vue';
import IconInput from '@/components/common/IconInput.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useFormValidation } from '@/composables/useFormValidation';
import { forgotPasswordSchema } from '@/lib/schemas';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Lupa kata sandi',
        description:
            'Kami kirim tautan untuk membuat kata sandi baru ke email akunmu.',
    },
});

defineProps<{
    status?: string;
}>();

// Validation runs here first (zod schema, Indonesian messages) and shows under each field. The
// browser's own bubble is switched off (novalidate). The server stays the final judge: its errors
// render in the same place.
const { shaking, validate, message, aria, clear, hasProblem } =
    useFormValidation(forgotPasswordSchema, ['email'] as const);
</script>

<template>
    <Head title="Lupa kata sandi" />

    <div
        v-if="status"
        class="mb-4 rounded-md border border-success/30 bg-success/10 px-3 py-2 text-sm font-semibold text-success"
        role="status"
    >
        {{ status }}
    </div>

    <Form
        v-bind="email.form()"
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

            <Button
                type="submit"
                class="mt-2 w-full"
                :disabled="processing"
                data-test="email-password-reset-link-button"
            >
                <Spinner v-if="processing" />
                <Send v-else aria-hidden="true" />
                {{ processing ? 'Mengirim...' : 'Kirim tautan reset' }}
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Atau kembali ke halaman
            <TextLink :href="login()">masuk</TextLink>
        </div>
    </Form>
</template>
