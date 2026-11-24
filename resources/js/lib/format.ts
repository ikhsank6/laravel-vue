const dateFormatter = new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
});

const dateTimeFormatter = new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
});

export function formatDate(value: string | null | undefined): string {
    return value ? dateFormatter.format(new Date(value)) : '';
}

export function formatDateTime(value: string | null | undefined): string {
    return value ? dateTimeFormatter.format(new Date(value)) : '';
}

export function slugify(value: string): string {
    return value
        .toLowerCase()
        .normalize('NFKD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');
}

export function paragraphs(value: string): string[] {
    return value
        .split(/\n{2,}/)
        .map((p) => p.trim())
        .filter(Boolean);
}
