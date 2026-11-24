<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { Mail, Save, User } from '@lucide/vue';
import { computed, reactive, ref } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import IconInput from '@/components/common/IconInput.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { firstErrors } from '@/lib/schemas';
import { profileSchema } from '@/lib/settingsSchemas';
import { send } from '@/routes/verification';

const page = usePage();
const user = computed(() => page.props.auth.user);

const name = ref(user.value.name);
const email = ref(user.value.email);

// Validation runs here first (zod schema, Indonesian messages) and shows under each field. The
// browser's own bubble is switched off (novalidate). The server stays the final judge: its errors
// render in the same place.
const clientErrors = reactive<{ name?: string; email?: string }>({});
const shaking = ref(false);

const validate = (event: Event) => {
    const form = event.target as HTMLFormElement;
    const data = new FormData(form);
    const result = profileSchema.safeParse({
        name: data.get('name') ?? '',
        email: data.get('email') ?? '',
    });

    clientErrors.name = undefined;
    clientErrors.email = undefined;

    if (result.success) {
        return;
    }

    Object.assign(clientErrors, firstErrors<'name' | 'email'>(result.error));

    event.preventDefault();
    event.stopImmediatePropagation();
    form.querySelector<HTMLElement>(
        clientErrors.name ? '#name' : '#email',
    )?.focus();

    shaking.value = false;
    requestAnimationFrame(() => {
        shaking.value = true;
        setTimeout(() => (shaking.value = false), 400);
    });
};

const nameMessage = (server?: string) => clientErrors.name ?? server;
const emailMessage = (server?: string) => clientErrors.email ?? server;
const hasProblem = computed(() => !!(clientErrors.name || clientErrors.email));
</script>

<template>
    <Head title="Pengaturan profil" />

    <h1 class="sr-only">Pengaturan profil</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Profil"
            description="Ubah nama dan alamat email Anda."
        />

        <Form
            v-bind="ProfileController.update.form()"
            :class="['space-y-6', shaking ? 'shake' : '']"
            novalidate
            :data-invalid="hasProblem || undefined"
            v-slot="{ errors, processing, clearErrors }"
            @submit.capture="validate"
        >
            <div class="grid gap-2">
                <Label for="name">Nama</Label>
                <IconInput
                    id="name"
                    v-model="name"
                    :icon="User"
                    class="mt-1"
                    name="name"
                    required
                    autocomplete="name"
                    placeholder="Nama lengkap"
                    :aria-invalid="!!nameMessage(errors.name)"
                    :aria-describedby="
                        nameMessage(errors.name) ? 'name-error' : undefined
                    "
                    @input="
                        clientErrors.name = undefined;
                        clearErrors('name');
                    "
                />
                <InputError
                    id="name-error"
                    :message="nameMessage(errors.name)"
                />
            </div>

            <div class="grid gap-2">
                <Label for="email">Alamat email</Label>
                <IconInput
                    id="email"
                    v-model="email"
                    :icon="Mail"
                    class="mt-1"
                    type="email"
                    name="email"
                    required
                    autocomplete="username"
                    placeholder="nama@lembaga.id"
                    :aria-invalid="!!emailMessage(errors.email)"
                    :aria-describedby="
                        emailMessage(errors.email) ? 'email-error' : undefined
                    "
                    @input="
                        clientErrors.email = undefined;
                        clearErrors('email');
                    "
                />
                <InputError
                    id="email-error"
                    :message="emailMessage(errors.email)"
                />
            </div>

            <div v-if="page.props.mustVerifyEmail && !user.email_verified_at">
                <p class="-mt-4 text-sm text-muted-foreground">
                    Alamat email Anda belum diverifikasi.
                    <Link
                        :href="send()"
                        as="button"
                        class="text-link underline underline-offset-4"
                    >
                        Kirim ulang email verifikasi.
                    </Link>
                </p>

                <div
                    v-if="page.props.status === 'verification-link-sent'"
                    class="mt-2 text-sm font-semibold text-success"
                >
                    Tautan verifikasi baru sudah dikirim ke email Anda.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button
                    :disabled="processing"
                    data-test="update-profile-button"
                >
                    <Spinner v-if="processing" />
                    <Save v-else class="size-4" aria-hidden="true" />
                    {{ processing ? 'Menyimpan...' : 'Simpan perubahan' }}
                </Button>
            </div>
        </Form>
    </div>

    <DeleteUser />
</template>
