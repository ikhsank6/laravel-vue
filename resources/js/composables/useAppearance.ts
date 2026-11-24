import type { ComputedRef, Ref } from 'vue';
import { computed, onMounted, ref } from 'vue';
import type { Surface } from '@/composables/useSurface';
import type { Appearance, ResolvedAppearance } from '@/types';

export type { Appearance, ResolvedAppearance };

export type UseAppearanceReturn = {
    appearance: Ref<Appearance>;
    resolvedAppearance: ComputedRef<ResolvedAppearance>;
    updateAppearance: (value: Appearance) => void;
};

// Each surface keeps its own preference; both default to light (DESIGN.md).
const DEFAULTS: Record<Surface, Appearance> = {
    website: 'light',
    admin: 'light',
};

const currentSurface = (): Surface =>
    typeof document !== 'undefined' &&
    document.documentElement.dataset.surface === 'website'
        ? 'website'
        : 'admin';

const prefersDark = (): boolean =>
    typeof window !== 'undefined' &&
    window.matchMedia('(prefers-color-scheme: dark)').matches;

const getStoredAppearance = (surface: Surface): Appearance | null => {
    if (typeof window === 'undefined') {
        return null;
    }

    try {
        return localStorage.getItem(
            `appearance:${surface}`,
        ) as Appearance | null;
    } catch {
        return null;
    }
};

const preferenceFor = (surface: Surface): Appearance =>
    getStoredAppearance(surface) ?? DEFAULTS[surface];

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

export function updateTheme(value: Appearance): void {
    if (typeof window === 'undefined') {
        return;
    }

    const dark = value === 'system' ? prefersDark() : value === 'dark';
    document.documentElement.classList.toggle('dark', dark);
}

const appearance = ref<Appearance>('light');

/** Re-applies the preference of the current surface, e.g. after moving between surfaces. */
export function syncThemeWithSurface(): void {
    appearance.value = preferenceFor(currentSurface());
    updateTheme(appearance.value);
}

export function initializeTheme(): void {
    if (typeof window === 'undefined') {
        return;
    }

    syncThemeWithSurface();

    window
        .matchMedia('(prefers-color-scheme: dark)')
        .addEventListener('change', () =>
            updateTheme(preferenceFor(currentSurface())),
        );
}

export function useAppearance(): UseAppearanceReturn {
    onMounted(() => {
        appearance.value = preferenceFor(currentSurface());
    });

    const resolvedAppearance = computed<ResolvedAppearance>(() => {
        if (appearance.value === 'system') {
            return prefersDark() ? 'dark' : 'light';
        }

        return appearance.value;
    });

    function updateAppearance(value: Appearance) {
        const surface = currentSurface();
        appearance.value = value;

        try {
            localStorage.setItem(`appearance:${surface}`, value);
        } catch {
            // Storage can be unavailable (private mode); the cookie still carries the choice.
        }

        setCookie(`appearance_${surface}`, value);
        updateTheme(value);
    }

    return {
        appearance,
        resolvedAppearance,
        updateAppearance,
    };
}
