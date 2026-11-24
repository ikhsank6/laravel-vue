<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Building2,
    ExternalLink,
    FileText,
    Link as LinkIcon,
    Mail,
    MapPin,
    Phone,
    Save,
    Type,
} from '@lucide/vue';
import type { Component } from 'vue';
import { reactive, ref } from 'vue';
import { toast } from 'vue-sonner';
import DemoNotice from '@/components/common/DemoNotice.vue';
import IconInput from '@/components/common/IconInput.vue';
import PageHeader from '@/components/common/PageHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { firstErrors } from '@/lib/schemas';
import { aboutSchema } from '@/lib/settingsSchemas';
import { about as aboutPage } from '@/routes';
import type { About } from '@/types';

const props = defineProps<{ about: About }>();

type FieldName =
    | 'name'
    | 'tagline'
    | 'description'
    | 'address'
    | 'email'
    | 'phone'
    | 'map_url';

type AboutField = {
    name: FieldName;
    label: string;
    icon: Component;
    type?: 'text' | 'email' | 'tel' | 'url' | 'textarea';
    required?: boolean;
    placeholder?: string;
    help?: string;
    autocomplete?: string;
    wide?: boolean;
};

const profileFields: AboutField[] = [
    {
        name: 'name',
        label: 'Nama organisasi',
        icon: Building2,
        required: true,
        autocomplete: 'organization',
    },
    {
        name: 'tagline',
        label: 'Tagline',
        icon: Type,
        help: 'Satu kalimat di bawah nama.',
    },
    {
        name: 'description',
        label: 'Profil',
        icon: FileText,
        type: 'textarea',
        required: true,
        help: 'Pisahkan paragraf dengan baris kosong.',
    },
];

const contactFields: AboutField[] = [
    {
        name: 'address',
        label: 'Alamat',
        icon: MapPin,
        type: 'textarea',
        placeholder: 'Alamat lengkap kantor',
        autocomplete: 'street-address',
    },
    {
        name: 'email',
        label: 'Email',
        icon: Mail,
        type: 'email',
        placeholder: 'info@domain.com',
        autocomplete: 'email',
    },
    {
        name: 'phone',
        label: 'Telepon',
        icon: Phone,
        type: 'tel',
        placeholder: '021 1234 5678',
        autocomplete: 'tel',
    },
    {
        name: 'map_url',
        label: 'Tautan Google Maps',
        icon: LinkIcon,
        type: 'url',
        placeholder: 'https://maps.google.com/...',
        wide: true,
    },
];

const fieldOrder: FieldName[] = [
    ...profileFields.map((field) => field.name),
    ...contactFields.map((field) => field.name),
];

const values = reactive<Record<FieldName, string>>({
    name: props.about.name ?? '',
    tagline: props.about.tagline ?? '',
    description: props.about.description ?? '',
    address: props.about.address ?? '',
    email: props.about.email ?? '',
    phone: props.about.phone ?? '',
    map_url: props.about.map_url ?? '',
});
const errors = reactive<Partial<Record<FieldName, string>>>({});
const shaking = ref(false);

const idOf = (name: FieldName) => `about-${name}`;
const describedBy = (field: AboutField) =>
    [
        field.help ? `${idOf(field.name)}-help` : '',
        errors[field.name] ? `${idOf(field.name)}-error` : '',
    ]
        .filter(Boolean)
        .join(' ') || undefined;

function clear(name: FieldName) {
    errors[name] = undefined;
}

// Validation runs here first (zod schema, Indonesian messages) and shows under each field. The
// browser's own bubble is switched off (novalidate). Saving is demo only, so nothing is sent.
function save() {
    const result = aboutSchema.safeParse({ ...values });

    for (const name of fieldOrder) {
        errors[name] = undefined;
    }

    if (result.success) {
        toast.success('Profil Tentang Kami disimpan.');

        return;
    }

    Object.assign(errors, firstErrors<FieldName>(result.error));

    const first = fieldOrder.find((name) => errors[name]);

    if (first) {
        document.getElementById(idOf(first))?.focus();
    }

    toast.error('Periksa kolom yang ditandai sebelum menyimpan.');

    shaking.value = false;
    requestAnimationFrame(() => {
        shaking.value = true;
        setTimeout(() => (shaking.value = false), 400);
    });
}
</script>

<template>
    <Head title="Tentang Kami" />

    <form novalidate :class="shaking ? 'shake' : ''" @submit.prevent="save">
        <PageHeader
            title="Tentang Kami"
            description="Isi halaman Tentang di website. Kolom kontak yang kosong tidak ditampilkan."
        >
            <template #actions>
                <Button variant="ghost" as-child>
                    <Link :href="aboutPage()">
                        Lihat halaman
                        <ExternalLink class="size-4" aria-hidden="true" />
                    </Link>
                </Button>
                <Button type="submit">
                    <Save class="size-4" aria-hidden="true" />
                    Simpan profil
                </Button>
            </template>
        </PageHeader>

        <div class="space-y-5">
            <DemoNotice />

            <section
                v-for="section in [
                    {
                        title: 'Profil',
                        text: 'Bagian utama halaman Tentang.',
                        fields: profileFields,
                    },
                    {
                        title: 'Kontak',
                        text: 'Ditampilkan hanya jika diisi.',
                        fields: contactFields,
                    },
                ]"
                :key="section.title"
                class="card-surface grid gap-6 p-5 sm:p-6 lg:grid-cols-[14rem_1fr]"
            >
                <div>
                    <h2 class="font-medium">{{ section.title }}</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ section.text }}
                    </p>
                </div>

                <div class="grid gap-x-4 gap-y-5 sm:grid-cols-2">
                    <div
                        v-for="field in section.fields"
                        :key="field.name"
                        :class="[
                            'grid content-start gap-2',
                            field.wide || field.type === 'textarea'
                                ? 'sm:col-span-2'
                                : '',
                        ]"
                    >
                        <Label :for="idOf(field.name)">
                            {{ field.label }}
                            <span v-if="field.required" class="text-destructive"
                                >*<span class="sr-only"> wajib</span></span
                            >
                        </Label>

                        <div v-if="field.type === 'textarea'" class="relative">
                            <component
                                :is="field.icon"
                                :class="[
                                    'pointer-events-none absolute top-3 left-3 size-4',
                                    errors[field.name]
                                        ? 'text-destructive'
                                        : 'text-muted-foreground',
                                ]"
                                aria-hidden="true"
                            />
                            <Textarea
                                :id="idOf(field.name)"
                                v-model="values[field.name]"
                                class="pl-9"
                                :name="field.name"
                                :placeholder="field.placeholder"
                                :autocomplete="field.autocomplete"
                                rows="4"
                                :aria-invalid="!!errors[field.name]"
                                :aria-describedby="describedBy(field)"
                                @input="clear(field.name)"
                            />
                        </div>

                        <IconInput
                            v-else
                            :id="idOf(field.name)"
                            v-model="values[field.name]"
                            :icon="field.icon"
                            :type="field.type ?? 'text'"
                            :name="field.name"
                            :placeholder="field.placeholder"
                            :autocomplete="field.autocomplete"
                            :aria-invalid="!!errors[field.name]"
                            :aria-describedby="describedBy(field)"
                            @input="clear(field.name)"
                        />

                        <p
                            v-if="field.help"
                            :id="`${idOf(field.name)}-help`"
                            class="text-xs text-muted-foreground"
                        >
                            {{ field.help }}
                        </p>
                        <InputError
                            :id="`${idOf(field.name)}-error`"
                            :message="errors[field.name]"
                        />
                    </div>
                </div>
            </section>
        </div>
    </form>
</template>
