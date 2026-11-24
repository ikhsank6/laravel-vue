export type Role = {
    id: number;
    name: string;
    slug: string;
    description: string;
    users_count: number;
};

export type ManagedUser = {
    id: number;
    name: string;
    email: string;
    role_id: number;
    role: string;
    is_active: boolean;
    last_login_at: string | null;
    created_at: string;
};

export type Menu = {
    id: number;
    name: string;
    route: string | null;
    icon: string;
    parent_id: number | null;
    parent_name: string | null;
    order: number;
    is_active: boolean;
};

export type NewsCategory = {
    id: number;
    name: string;
    slug: string;
    description: string;
};

export type NewsStatus = 'draft' | 'published';

export type News = {
    id: number;
    title: string;
    slug: string;
    category_id: number;
    category_name: string;
    status: NewsStatus;
    published_at: string | null;
    excerpt: string;
    content: string;
    updated_at: string;
};

export type Carousel = {
    id: number;
    title: string;
    subtitle: string;
    link: string | null;
    link_label: string | null;
    order: number;
    is_active: boolean;
};

export type About = {
    name: string;
    tagline: string;
    description: string;
    address: string | null;
    email: string | null;
    phone: string | null;
    map_url: string | null;
};

export type SystemSetting = {
    id: number;
    key: string;
    label: string;
    value: string;
    group: string;
};

export type LogLevel = 'info' | 'warning' | 'error';

export type LogEntry = {
    id: number;
    level: LogLevel;
    message: string;
    context: string;
    logged_at: string;
};

export type FailedJob = {
    id: number;
    uuid: string;
    job: string;
    queue: string;
    exception: string;
    failed_at: string;
};
