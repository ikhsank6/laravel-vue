import {
    Building2,
    GalleryHorizontal,
    KeyRound,
    LayoutDashboard,
    ListTree,
    ListX,
    Newspaper,
    ScrollText,
    Shield,
    SlidersHorizontal,
    Tags,
    Users,
} from '@lucide/vue';
import { dashboard } from '@/routes';
import { edit as aboutEdit } from '@/routes/admin/about';
import { index as carousels } from '@/routes/admin/carousels';
import { index as failedJobs } from '@/routes/admin/failed-jobs';
import { index as logs } from '@/routes/admin/logs';
import { index as menuAccess } from '@/routes/admin/menu-access';
import { index as menus } from '@/routes/admin/menus';
import { index as news } from '@/routes/admin/news';
import { index as newsCategories } from '@/routes/admin/news-categories';
import { index as roles } from '@/routes/admin/roles';
import { index as systemSettings } from '@/routes/admin/system-settings';
import { index as users } from '@/routes/admin/users';
import type { NavItem } from '@/types';

export type NavGroup = {
    label: string | null;
    items: NavItem[];
};

// Icons are picked for the object each page manages (people, shield for permissions,
// scroll for logs), not for decoration.
export const adminNavigation: NavGroup[] = [
    {
        label: null,
        items: [
            { title: 'Dashboard', href: dashboard(), icon: LayoutDashboard },
        ],
    },
    {
        label: 'CMS',
        items: [
            { title: 'Berita', href: news(), icon: Newspaper },
            { title: 'Kategori Berita', href: newsCategories(), icon: Tags },
            {
                title: 'Slide Beranda',
                href: carousels(),
                icon: GalleryHorizontal,
            },
            { title: 'Tentang Kami', href: aboutEdit(), icon: Building2 },
        ],
    },
    {
        label: 'Master Data',
        items: [
            { title: 'Pengguna', href: users(), icon: Users },
            { title: 'Peran', href: roles(), icon: Shield },
            { title: 'Menu', href: menus(), icon: ListTree },
            { title: 'Akses Menu', href: menuAccess(), icon: KeyRound },
        ],
    },
    {
        label: 'Sistem',
        items: [
            {
                title: 'Pengaturan',
                href: systemSettings(),
                icon: SlidersHorizontal,
            },
            { title: 'Log', href: logs(), icon: ScrollText },
            { title: 'Antrian Gagal', href: failedJobs(), icon: ListX },
        ],
    },
];
