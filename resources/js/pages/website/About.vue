<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Mail, MapPin, Phone } from '@lucide/vue';
import { computed } from 'vue';
import { paragraphs } from '@/lib/format';
import type { About } from '@/types';

const props = defineProps<{ about: About }>();

const body = computed(() => paragraphs(props.about.description));

// Only filled contact fields render; empty ones are simply absent, never placeholder text.
const contacts = computed(() =>
    [
        {
            icon: MapPin,
            label: 'Alamat',
            value: props.about.address,
            href: props.about.map_url,
        },
        {
            icon: Mail,
            label: 'Email',
            value: props.about.email,
            href: props.about.email ? `mailto:${props.about.email}` : null,
        },
        {
            icon: Phone,
            label: 'Telepon',
            value: props.about.phone,
            href: props.about.phone
                ? `tel:${props.about.phone.replace(/\s+/g, '')}`
                : null,
        },
    ].filter((contact) => contact.value),
);
</script>

<template>
    <Head title="Tentang" />

    <section class="tile-dark px-4 py-20 sm:px-6">
        <div class="mx-auto max-w-4xl text-center">
            <h1
                class="rise font-display text-4xl font-semibold tracking-tight sm:text-5xl lg:text-[56px] lg:leading-[1.07]"
            >
                {{ about.name }}
            </h1>
            <p
                v-if="about.tagline"
                class="mt-4 text-xl text-muted-foreground sm:text-2xl"
            >
                {{ about.tagline }}
            </p>
        </div>
    </section>
    <div class="rule-guilloche" aria-hidden="true" />

    <div class="tile-light px-4 pt-16 pb-24 sm:px-6">
        <div class="mx-auto grid max-w-6xl gap-10 md:grid-cols-[2fr_1fr]">
            <div
                class="max-w-[68ch] space-y-5 text-[17px] leading-relaxed sm:text-xl sm:leading-[1.6]"
            >
                <p v-for="(paragraph, i) in body" :key="i">{{ paragraph }}</p>
            </div>

            <aside
                class="tile-parchment h-fit rounded-lg p-6"
                aria-labelledby="contact-title"
            >
                <h2 id="contact-title" class="text-lg font-semibold">Kontak</h2>
                <dl v-if="contacts.length" class="mt-4 space-y-4">
                    <div
                        v-for="contact in contacts"
                        :key="contact.label"
                        class="flex gap-3"
                    >
                        <component
                            :is="contact.icon"
                            class="mt-0.5 size-4 shrink-0 text-muted-foreground"
                            aria-hidden="true"
                        />
                        <div class="min-w-0">
                            <dt class="text-sm text-muted-foreground">
                                {{ contact.label }}
                            </dt>
                            <dd class="text-sm break-words">
                                <a
                                    v-if="contact.href"
                                    :href="contact.href"
                                    class="text-link underline-offset-4 hover:underline"
                                    >{{ contact.value }}</a
                                >
                                <span v-else>{{ contact.value }}</span>
                            </dd>
                        </div>
                    </div>
                </dl>
                <p v-else class="mt-2 text-sm text-muted-foreground">
                    Kontak resmi belum dicantumkan.
                </p>
            </aside>
        </div>
    </div>
</template>
