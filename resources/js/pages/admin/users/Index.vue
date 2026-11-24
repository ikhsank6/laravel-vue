<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { User, Users } from '@lucide/vue';
import { computed } from 'vue';
import ResourceIndex from '@/components/common/ResourceIndex.vue';
import StatusBadge from '@/components/common/StatusBadge.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import { formatDateTime } from '@/lib/format';
import type { Column, Field, FieldValues, ManagedUser, Role } from '@/types';

const props = defineProps<{
    rows?: ManagedUser[];
    roles: Role[];
}>();

const { getInitials } = useInitials();

const columns: Column[] = [
    { key: 'name', label: 'Pengguna' },
    { key: 'role', label: 'Peran', hideBelow: 'sm' },
    { key: 'is_active', label: 'Status' },
    { key: 'last_login_at', label: 'Terakhir masuk', hideBelow: 'md' },
];

const fields = computed<Field[]>(() => [
    {
        name: 'name',
        label: 'Nama',
        type: 'text',
        required: true,
        placeholder: 'Nama lengkap',
        icon: User,
        maxLength: 255,
    },
    {
        name: 'email',
        label: 'Email',
        type: 'email',
        required: true,
        placeholder: 'email@example.com',
        maxLength: 255,
    },
    {
        name: 'role_id',
        label: 'Peran',
        type: 'select',
        required: true,
        options: props.roles.map((role) => ({
            label: role.name,
            value: role.id,
        })),
    },
    { name: 'is_active', label: 'Akun aktif', type: 'switch' },
]);

const toRow = (
    values: FieldValues,
    existing?: ManagedUser,
): Omit<ManagedUser, 'id'> => ({
    name: String(values.name),
    email: String(values.email),
    role_id: Number(values.role_id),
    role:
        props.roles.find((role) => role.id === Number(values.role_id))?.name ??
        '',
    is_active: Boolean(values.is_active),
    last_login_at: existing?.last_login_at ?? null,
    created_at: existing?.created_at ?? new Date().toISOString(),
});
</script>

<template>
    <Head title="Pengguna" />

    <ResourceIndex
        title="Pengguna"
        description="Akun yang bisa masuk ke panel admin. Peran menentukan menu yang terlihat."
        entity="pengguna"
        :rows="rows"
        :columns="columns"
        :fields="fields"
        :search-keys="['name', 'email', 'role']"
        :defaults="{
            name: '',
            email: '',
            role_id: roles[0]?.id,
            is_active: true,
        }"
        :row-label="(row) => row.name"
        :to-row="toRow"
        :empty-icon="Users"
        empty-description="Tambahkan akun untuk editor atau penulis yang akan mengelola konten."
    >
        <template #cell-name="{ row }">
            <div class="flex items-center gap-3">
                <Avatar class="size-9">
                    <AvatarFallback class="bg-muted text-xs font-medium">
                        {{ getInitials(row.name) }}
                    </AvatarFallback>
                </Avatar>
                <div class="min-w-0">
                    <p class="truncate font-medium">{{ row.name }}</p>
                    <p class="truncate text-xs text-muted-foreground">
                        {{ row.email }}
                    </p>
                </div>
            </div>
        </template>
        <template #cell-is_active="{ row }">
            <StatusBadge
                :tone="row.is_active ? 'success' : 'neutral'"
                :label="row.is_active ? 'Aktif' : 'Nonaktif'"
            />
        </template>
        <template #cell-last_login_at="{ row }">
            <span class="text-muted-foreground">
                {{
                    row.last_login_at
                        ? formatDateTime(row.last_login_at)
                        : 'Belum pernah'
                }}
            </span>
        </template>
    </ResourceIndex>
</template>
