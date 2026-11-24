import type { InertiaLinkProps } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavGroup } from '@/lib/adminNavigation';
import { adminNavigation } from '@/lib/adminNavigation';

type Href = NonNullable<InertiaLinkProps['href']>;

/** Active state and breadcrumb context for the admin navigation. */
export function useAdminNavigation() {
    const { isCurrentUrl } = useCurrentUrl();

    const isActive = (href: Href) => isCurrentUrl(href);
    const isGroupActive = (group: NavGroup) =>
        group.items.some((item) => isActive(item.href));

    const currentGroup = computed(() =>
        adminNavigation.find((group) => isGroupActive(group)),
    );

    return { groups: adminNavigation, isActive, isGroupActive, currentGroup };
}
