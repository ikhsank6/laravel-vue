import type { MaybeRefOrGetter } from 'vue';
import { computed, ref, toValue, watch } from 'vue';
import type { Row } from '@/types';

type Options = {
    searchKeys: string[];
    perPage?: number;
};

function toSearchText(value: unknown): string {
    return typeof value === 'string' || typeof value === 'number'
        ? String(value).toLowerCase()
        : '';
}

/** Client-side search, sorting and pagination over a list of rows. */
export function useTableQuery<T extends Row>(
    rows: MaybeRefOrGetter<T[]>,
    { searchKeys, perPage = 10 }: Options,
) {
    const search = ref('');
    const page = ref(1);
    const pageSize = ref(perPage);

    const sortKey = ref<string>();
    const sortDir = ref<'asc' | 'desc'>('asc');

    // none -> ascending -> descending -> none, per column.
    function toggleSort(key: string) {
        if (sortKey.value !== key) {
            sortKey.value = key;
            sortDir.value = 'asc';
        } else if (sortDir.value === 'asc') {
            sortDir.value = 'desc';
        } else {
            sortKey.value = undefined;
            sortDir.value = 'asc';
        }
    }

    const filtered = computed(() => {
        const term = search.value.trim().toLowerCase();
        const list = toValue(rows);
        const matched = term
            ? list.filter((row) =>
                  searchKeys.some((key) =>
                      toSearchText(row[key]).includes(term),
                  ),
              )
            : list;
        const key = sortKey.value;

        if (!key) {
            return matched;
        }

        const direction = sortDir.value === 'asc' ? 1 : -1;

        return [...matched].sort(
            (a, b) =>
                direction *
                toSearchText(a[key]).localeCompare(toSearchText(b[key]), 'id', {
                    numeric: true,
                    sensitivity: 'base',
                }),
        );
    });

    const lastPage = computed(() =>
        Math.max(1, Math.ceil(filtered.value.length / pageSize.value)),
    );

    const paged = computed(() =>
        filtered.value.slice(
            (page.value - 1) * pageSize.value,
            page.value * pageSize.value,
        ),
    );

    watch([search, pageSize], () => (page.value = 1));
    watch(lastPage, (last) => {
        if (page.value > last) {
            page.value = last;
        }
    });

    return {
        search,
        page,
        perPage: pageSize,
        filtered,
        paged,
        lastPage,
        sortKey,
        sortDir,
        toggleSort,
    };
}
