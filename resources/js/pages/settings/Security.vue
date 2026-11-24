<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { KeyRound, Lock, ShieldCheck } from '@lucide/vue';
import { computed, reactive, ref } from 'vue';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { firstErrors } from '@/lib/schemas';
import { passwordChangeSchema } from '@/lib/settingsSchemas';
import type { Props as ManagePasskeysProps } from '@/components/ManagePasskeys.vue';
import ManagePasskeys from '@/components/ManagePasskeys.vue';
import type { Props as ManageTwoFactorProps } from '@/components/ManageTwoFactor.vue';
import ManageTwoFactor from '@/components/ManageTwoFactor.vue';

// oxfmt-ignore
type Props = {
    passwordRules: string;
} & ManagePasskeysProps &
    ManageTwoFactorProps;

const props = defineProps<Props>();

type FieldName = 'current_password' | 'password' | 'password_confirmation';

const fieldOrder: FieldName[] = [
    'current_password',
    'password',
    'password_confirmation',
];

// Validation runs here first (zod schema, Indonesian messages) and shows under each field. The
// browser's own bubble is switched off (novalidate). The server stays the final judge: its errors
// render in the same place.
const clientErrors = reactive<Partial<Record<FieldName, string>>>({});
const shaking = ref(false);

const validate = (event: Event) => {
    const form = event.target as HTMLFormElement;
    const data = new FormData(form);
    const result = passwordChangeSchema.safeParse({
        current_password: data.get('current_password') ?? '',
        password: data.get('password') ?? '',
        password_confirmation: data.get('password_confirmation') ?? '',
    });

    for (const field of fieldOrder) {
        clientErrors[field] = undefined;
    }

    if (result.success) {
        return;
    }

    Object.assign(clientErrors, firstErrors<FieldName>(result.error));

    event.preventDefault();
    event.stopImmediatePropagation();

    const first = fieldOrder.find((field) => clientErrors[field]);

    if (first) {
        form.querySelector<HTMLElement>(`#${first}`)?.focus();
    }

    shaking.value = false;
    requestAnimationFrame(() => {
        shaking.value = true;
        setTimeout(() => (shaking.value = false), 400);
    });
};

const message = (field: FieldName, server?: string) =>
    clientErrors[field] ?? server;
const hasProblem = computed(() => fieldOrder.some((f) => clientErrors[f]));
</script>

<template>
    <Head title="Pengaturan keamanan" />

    <h1 class="sr-only">Pengaturan keamanan</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Ubah kata sandi"
            description="Gunakan kata sandi yang panjang dan acak agar akun Anda tetap aman."
        />

        <Form
            v-bind="SecurityController.update.form()"
            :options="{
                preserveScroll: true,
            }"
            reset-on-success
            :reset-on-error="[
                'password',
                'password_confirmation',
                'current_password',
            ]"
            :class="['space-y-6', shaking ? 'shake' : '']"
            novalidate
            :data-invalid="hasProblem || undefined"
            v-slot="{ errors, processing, clearErrors }"
            @submit.capture="validate"
        >
            <div class="grid gap-2">
                <Label for="current_password">Kata sandi saat ini</Label>
                <div class="relative mt-1">
                    <KeyRound
                        :class="[
                            'pointer-events-none absolute top-1/2 left-3 z-10 size-4 -translate-y-1/2',
                            message('current_password', errors.current_password)
                                ? 'text-destructive'
                                : 'text-muted-foreground',
                        ]"
                        aria-hidden="true"
                    />
                    <PasswordInput
                        id="current_password"
                        name="current_password"
                        class="block w-full pl-9"
                        autocomplete="current-password"
                        placeholder="Kata sandi saat ini"
                        :aria-invalid="
                            !!message(
                                'current_password',
                                errors.current_password,
                            )
                        "
                        :aria-describedby="
                            message('current_password', errors.current_password)
                                ? 'current_password-error'
                                : undefined
                        "
                        @input="
                            clientErrors.current_password = undefined;
                            clearErrors('current_password');
                        "
                    />
                </div>
                <InputError
                    id="current_password-error"
                    :message="
                        message('current_password', errors.current_password)
                    "
                />
            </div>

            <div class="grid gap-2">
                <Label for="password">Kata sandi baru</Label>
                <div class="relative mt-1">
                    <Lock
                        :class="[
                            'pointer-events-none absolute top-1/2 left-3 z-10 size-4 -translate-y-1/2',
                            message('password', errors.password)
                                ? 'text-destructive'
                                : 'text-muted-foreground',
                        ]"
                        aria-hidden="true"
                    />
                    <PasswordInput
                        id="password"
                        name="password"
                        class="block w-full pl-9"
                        autocomplete="new-password"
                        placeholder="Kata sandi baru"
                        :passwordrules="props.passwordRules"
                        :aria-invalid="!!message('password', errors.password)"
                        :aria-describedby="
                            message('password', errors.password)
                                ? 'password-error'
                                : undefined
                        "
                        @input="
                            clientErrors.password = undefined;
                            clearErrors('password');
                        "
                    />
                </div>
                <InputError
                    id="password-error"
                    :message="message('password', errors.password)"
                />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation"
                    >Konfirmasi kata sandi baru</Label
                >
                <div class="relative mt-1">
                    <Lock
                        :class="[
                            'pointer-events-none absolute top-1/2 left-3 z-10 size-4 -translate-y-1/2',
                            message(
                                'password_confirmation',
                                errors.password_confirmation,
                            )
                                ? 'text-destructive'
                                : 'text-muted-foreground',
                        ]"
                        aria-hidden="true"
                    />
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        class="block w-full pl-9"
                        autocomplete="new-password"
                        placeholder="Ulangi kata sandi baru"
                        :passwordrules="props.passwordRules"
                        :aria-invalid="
                            !!message(
                                'password_confirmation',
                                errors.password_confirmation,
                            )
                        "
                        :aria-describedby="
                            message(
                                'password_confirmation',
                                errors.password_confirmation,
                            )
                                ? 'password_confirmation-error'
                                : undefined
                        "
                        @input="
                            clientErrors.password_confirmation = undefined;
                            clearErrors('password_confirmation');
                        "
                    />
                </div>
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

            <div class="flex items-center gap-4">
                <Button
                    :disabled="processing"
                    data-test="update-password-button"
                >
                    <Spinner v-if="processing" />
                    <ShieldCheck v-else class="size-4" aria-hidden="true" />
                    {{ processing ? 'Menyimpan...' : 'Simpan kata sandi' }}
                </Button>
            </div>
        </Form>
    </div>

    <ManageTwoFactor
        :canManageTwoFactor="canManageTwoFactor"
        :requiresConfirmation="requiresConfirmation"
        :twoFactorEnabled="twoFactorEnabled"
    />

    <ManagePasskeys
        :canManagePasskeys="canManagePasskeys"
        :passkeys="passkeys"
    />
</template>
