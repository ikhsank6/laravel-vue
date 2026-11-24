// Plus Jakarta Sans (UI) and Literata (formal headlines), bundled locally so the site works offline (DESIGN.md).
import '@fontsource-variable/literata';
import '@fontsource-variable/plus-jakarta-sans';
import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import WebsiteLayout from '@/layouts/WebsiteLayout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import { vReveal } from '@/lib/reveal';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name.startsWith('website/'):
                return WebsiteLayout;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    withApp: (app) => {
        app.directive('reveal', vReveal);
        app.directive('focus', {
            mounted: (el: HTMLElement, shouldFocus) => {
                if (shouldFocus.value !== false) {
                    el.focus();
                }
            },
        });
    },
    progress: {
        color: '#2563a8',
    },
});

// Applies the saved theme on load; light is the default.
initializeTheme();

// Listens for flash toast data from the server.
initializeFlashToast();
