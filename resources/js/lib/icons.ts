import {
    ClipboardList,
    Laptop,
    Layers,
    Megaphone,
    PenTool,
    Wallet,
} from '@lucide/vue';
import type { Component } from 'vue';
import { slugify } from '@/lib/format';

// One icon per scheme field, keyed by the slug of the field name.
const fieldIcons: Record<string, Component> = {
    'teknologi-informasi': Laptop,
    administrasi: ClipboardList,
    keuangan: Wallet,
    'desain-kreatif': PenTool,
    pemasaran: Megaphone,
};

export function fieldIcon(fieldName: string): Component {
    return fieldIcons[slugify(fieldName)] ?? Layers;
}
