import { syncThemeWithSurface } from '@/composables/useAppearance';

export type Surface = 'website' | 'admin';

/**
 * Marks which design surface the current page belongs to. The root template sets it on
 * the first load; layouts call this so client-side navigation between the website and the
 * admin panel switches tokens and that surface's theme, including for dialogs teleported
 * to <body>.
 */
export function useSurface(surface: Surface): void {
    if (typeof document === 'undefined') {
        return;
    }

    if (document.documentElement.dataset.surface !== surface) {
        document.documentElement.dataset.surface = surface;
        syncThemeWithSurface();
    }
}
