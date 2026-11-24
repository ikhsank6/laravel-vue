import type { Ref } from 'vue';
import { ref, watch } from 'vue';
import type { Row } from '@/types';

/**
 * In-memory copy of a server collection for the UI-first phase. Changes live until the next
 * page load; swap these methods for Inertia form submissions once the backend persists data.
 */
export function useLocalCollection<T extends Row>(
    source: Ref<T[] | undefined>,
) {
    const items = ref<T[]>([]) as Ref<T[]>;

    watch(source, (value) => (items.value = value ? [...value] : []), {
        immediate: true,
    });

    const nextId = () =>
        items.value.reduce(
            (max, row) => Math.max(max, Number(row.id) || 0),
            0,
        ) + 1;

    function create(values: Omit<T, 'id'>): T {
        const row = { ...values, id: nextId() } as T;
        items.value = [row, ...items.value];

        return row;
    }

    function update(id: T['id'], values: Partial<T>): void {
        items.value = items.value.map((row) =>
            row.id === id ? { ...row, ...values } : row,
        );
    }

    function remove(id: T['id']): void {
        items.value = items.value.filter((row) => row.id !== id);
    }

    return { items, create, update, remove };
}
