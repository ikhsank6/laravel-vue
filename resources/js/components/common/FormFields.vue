<script setup lang="ts">
import {
    ArrowDownUp,
    CalendarDays,
    CircleDot,
    Hash,
    Link,
    Link2,
    ListTree,
    Lock,
    Mail,
    MousePointerClick,
    Phone,
    Route,
    Shapes,
    ShieldCheck,
    Tag,
    Type,
} from '@lucide/vue';
import type { Component } from 'vue';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import type { Field, FieldValues } from '@/types';
import IconInput from './IconInput.vue';

const values = defineModel<FieldValues>({ required: true });

const emit = defineEmits<{ change: [name: string] }>();

const props = withDefaults(
    defineProps<{
        fields: Field[];
        errors?: Record<string, string>;
        isEdit?: boolean;
        idPrefix?: string;
    }>(),
    { errors: () => ({}), isEdit: false, idPrefix: 'field' },
);

const set = (name: string, value: unknown) => {
    values.value = { ...values.value, [name]: value };
    emit('change', name);
};

// Leading icon: explicit `field.icon`, else by type, else by field name. No match, no icon.
const typeIcons: Partial<Record<Field['type'], Component>> = {
    email: Mail,
    url: Link,
    password: Lock,
    number: Hash,
    date: CalendarDays,
};

const nameIcons: Record<string, Component> = {
    title: Type,
    name: Type,
    label: Type,
    link_label: MousePointerClick,
    slug: Link2,
    phone: Phone,
    order: ArrowDownUp,
    sort: ArrowDownUp,
    route: Route,
    icon: Shapes,
    category_id: Tag,
    role_id: ShieldCheck,
    parent_id: ListTree,
    status: CircleDot,
};

const iconFor = (field: Field): Component | undefined =>
    field.icon ?? typeIcons[field.type] ?? nameIcons[field.name];

// An emptied number input stays empty so "wajib diisi" can fire instead of a silent 0.
const onInput = (field: Field, value: string | number) =>
    set(
        field.name,
        field.type === 'number' && value !== '' ? Number(value) : value,
    );

const describedBy = (field: Field) =>
    props.errors[field.name]
        ? `${props.idPrefix}-${field.name}-error`
        : undefined;
</script>

<template>
    <div class="grid gap-x-4 gap-y-5 sm:grid-cols-2">
        <div
            v-for="field in fields"
            :key="field.name"
            :class="[
                'grid content-start gap-2',
                field.wide || field.type === 'textarea' ? 'sm:col-span-2' : '',
            ]"
        >
            <template v-if="field.type === 'switch'">
                <div class="flex min-h-11 items-center justify-between gap-4">
                    <Label :for="`${idPrefix}-${field.name}`">{{
                        field.label
                    }}</Label>
                    <Switch
                        :id="`${idPrefix}-${field.name}`"
                        :model-value="Boolean(values[field.name])"
                        @update:model-value="set(field.name, $event)"
                    />
                </div>
            </template>

            <template v-else>
                <Label :for="`${idPrefix}-${field.name}`">
                    {{ field.label }}
                    <span v-if="field.required" class="text-destructive"
                        >*<span class="sr-only"> wajib</span></span
                    >
                </Label>

                <Textarea
                    v-if="field.type === 'textarea'"
                    :id="`${idPrefix}-${field.name}`"
                    :model-value="String(values[field.name] ?? '')"
                    :placeholder="field.placeholder"
                    :aria-invalid="!!errors[field.name]"
                    :aria-describedby="describedBy(field)"
                    rows="4"
                    @update:model-value="set(field.name, $event)"
                />

                <Select
                    v-else-if="field.type === 'select'"
                    :model-value="values[field.name] as string | number"
                    @update:model-value="set(field.name, $event)"
                >
                    <SelectTrigger
                        :id="`${idPrefix}-${field.name}`"
                        class="w-full"
                        :aria-invalid="!!errors[field.name]"
                        :aria-describedby="describedBy(field)"
                    >
                        <span class="flex min-w-0 items-center gap-2">
                            <component
                                :is="iconFor(field)"
                                v-if="iconFor(field)"
                                :class="[
                                    'size-4',
                                    errors[field.name]
                                        ? 'text-destructive'
                                        : 'text-muted-foreground',
                                ]"
                                aria-hidden="true"
                            />
                            <SelectValue
                                class="truncate"
                                :placeholder="field.placeholder ?? 'Pilih'"
                            />
                        </span>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="option in field.options"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>

                <IconInput
                    v-else-if="iconFor(field)"
                    :id="`${idPrefix}-${field.name}`"
                    :icon="iconFor(field)!"
                    :type="field.type"
                    :model-value="(values[field.name] as string | number) ?? ''"
                    :placeholder="field.placeholder"
                    :disabled="isEdit && field.disabledOnEdit"
                    :invalid="!!errors[field.name]"
                    :aria-invalid="!!errors[field.name]"
                    :aria-describedby="describedBy(field)"
                    @update:model-value="onInput(field, $event)"
                />

                <Input
                    v-else
                    :id="`${idPrefix}-${field.name}`"
                    :type="field.type"
                    :model-value="(values[field.name] as string | number) ?? ''"
                    :placeholder="field.placeholder"
                    :disabled="isEdit && field.disabledOnEdit"
                    :aria-invalid="!!errors[field.name]"
                    :aria-describedby="describedBy(field)"
                    @update:model-value="onInput(field, $event)"
                />

                <p v-if="field.help" class="text-xs text-muted-foreground">
                    {{ field.help }}
                </p>
                <InputError
                    :id="`${idPrefix}-${field.name}-error`"
                    :message="errors[field.name]"
                />
            </template>
        </div>
    </div>
</template>
