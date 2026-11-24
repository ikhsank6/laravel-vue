import { router, usePage } from '@inertiajs/vue3';
import type { Ref } from 'vue';
import { computed } from 'vue';

/**
 * Loading and error state for an Inertia deferred prop. The server marks a prop as rescued
 * when its resolver threw, which is what the error state reads.
 */
export function useDeferredProp(key: string, value: Ref<unknown>) {
    const page = usePage();

    const isError = computed(() => page.rescuedProps?.includes(key) ?? false);
    const isLoading = computed(
        () => value.value === undefined && !isError.value,
    );

    const retry = () => router.reload({ only: [key] });

    return { isLoading, isError, retry };
}
