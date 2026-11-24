import { useWindowScroll } from '@vueuse/core';
import { computed } from 'vue';

/** True once the page has scrolled past the top, so sticky bars can show a separating shadow. */
export function useScrolled() {
    const { y } = useWindowScroll();

    return computed(() => y.value > 4);
}
