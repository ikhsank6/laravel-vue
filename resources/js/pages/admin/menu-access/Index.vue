<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import DemoNotice from '@/components/common/DemoNotice.vue';
import PageHeader from '@/components/common/PageHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import type { Menu, Role } from '@/types';

const props = defineProps<{
    roles: Role[];
    menus: Menu[];
    access: Record<number, number[]>;
}>();

type AccessMap = Record<number, number[]>;

// Props are reactive proxies, which structuredClone rejects; copy the arrays instead.
const copyAccess = (access: AccessMap): AccessMap =>
    Object.fromEntries(
        Object.entries(access).map(([id, menus]) => [id, [...menus]]),
    );

const saved = ref<AccessMap>(copyAccess(props.access));
const draft = ref<AccessMap>(copyAccess(props.access));
const roleId = ref<number>(props.roles[0]?.id ?? 0);

const role = computed(() => props.roles.find((r) => r.id === roleId.value));

// Leaf menus open a page and are toggled directly; parent menus only group them and
// become visible when any child is allowed.
const sections = computed(() => {
    const roots = props.menus
        .filter((m) => m.parent_id === null)
        .sort((a, b) => a.order - b.order);

    return roots.map((root) => ({
        root,
        children: props.menus
            .filter((m) => m.parent_id === root.id)
            .sort((a, b) => a.order - b.order),
    }));
});

const selected = computed(() => new Set(draft.value[roleId.value] ?? []));

function setMember(set: Set<number>, id: number, present: boolean) {
    if (present) {
        set.add(id);
    } else {
        set.delete(id);
    }
}

function toggle(menu: Menu, checked: boolean) {
    const next = new Set(selected.value);
    setMember(next, menu.id, checked);

    if (menu.parent_id !== null) {
        const siblings = props.menus.filter(
            (m) => m.parent_id === menu.parent_id,
        );
        setMember(
            next,
            menu.parent_id,
            siblings.some((m) => next.has(m.id)),
        );
    }

    draft.value = { ...draft.value, [roleId.value]: [...next] };
}

const isDirty = computed(() => {
    const a = [...(draft.value[roleId.value] ?? [])].sort().join();
    const b = [...(saved.value[roleId.value] ?? [])].sort().join();

    return a !== b;
});

function save() {
    saved.value = { ...saved.value, [roleId.value]: [...selected.value] };
    toast.success(`Akses menu untuk ${role.value?.name} disimpan.`);
}

function reset() {
    draft.value = {
        ...draft.value,
        [roleId.value]: [...(saved.value[roleId.value] ?? [])],
    };
}

const countFor = (id: number) =>
    (draft.value[id] ?? []).filter(
        (menuId) => props.menus.find((m) => m.id === menuId)?.route,
    ).length;
</script>

<template>
    <Head title="Akses Menu" />

    <PageHeader
        title="Akses Menu"
        description="Pilih peran, lalu centang halaman yang boleh dibuka. Sidebar pengguna mengikuti pilihan ini."
    >
        <template #actions>
            <Button v-if="isDirty" variant="ghost" @click="reset"
                >Batalkan perubahan</Button
            >
            <Button :disabled="!isDirty" @click="save">Simpan akses</Button>
        </template>
    </PageHeader>

    <div class="space-y-5">
        <DemoNotice />

        <div class="overflow-x-auto pb-1">
            <Tabs
                :model-value="roleId"
                @update:model-value="roleId = Number($event)"
            >
                <TabsList aria-label="Pilih peran">
                    <TabsTrigger v-for="r in roles" :key="r.id" :value="r.id">
                        {{ r.name }}
                        <Badge variant="secondary" size="count">{{
                            countFor(r.id)
                        }}</Badge>
                    </TabsTrigger>
                </TabsList>
            </Tabs>
        </div>

        <p v-if="role" class="text-sm text-muted-foreground">
            {{ role.description }}
        </p>

        <div class="grid gap-4 lg:grid-cols-2">
            <fieldset
                v-for="section in sections"
                :key="section.root.id"
                class="card-surface p-4"
            >
                <template v-if="section.children.length">
                    <legend class="float-left mb-2 w-full text-sm font-medium">
                        {{ section.root.name }}
                    </legend>
                    <div class="clear-both grid gap-1">
                        <Label
                            v-for="menu in section.children"
                            :key="menu.id"
                            :for="`menu-${menu.id}`"
                            class="flex min-h-11 cursor-pointer items-center justify-between gap-3 rounded-md px-2 font-normal hover:bg-accent"
                        >
                            <span>
                                {{ menu.name }}
                                <code
                                    class="ml-2 hidden text-xs text-muted-foreground sm:inline"
                                    >{{ menu.route }}</code
                                >
                            </span>
                            <Checkbox
                                :id="`menu-${menu.id}`"
                                :model-value="selected.has(menu.id)"
                                @update:model-value="
                                    toggle(menu, $event === true)
                                "
                            />
                        </Label>
                    </div>
                </template>
                <Label
                    v-else
                    :for="`menu-${section.root.id}`"
                    class="flex min-h-11 cursor-pointer items-center justify-between gap-3 rounded-md px-2 font-medium hover:bg-accent"
                >
                    {{ section.root.name }}
                    <Checkbox
                        :id="`menu-${section.root.id}`"
                        :model-value="selected.has(section.root.id)"
                        @update:model-value="
                            toggle(section.root, $event === true)
                        "
                    />
                </Label>
            </fieldset>
        </div>
    </div>
</template>
