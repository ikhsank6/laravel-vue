import type { Component } from 'vue';

export type Row = { id: number | string } & Record<string, unknown>;

export type Column = {
    key: string;
    label: string;
    class?: string;
    /** Header becomes a sort button (client-side). */
    sortable?: boolean;
    /** Hide the column below this breakpoint to keep narrow tables readable. */
    hideBelow?: 'sm' | 'md' | 'lg';
};

export type FieldOption = { label: string; value: string | number };

export type FieldType =
    | 'text'
    | 'email'
    | 'url'
    | 'password'
    | 'date'
    | 'number'
    | 'textarea'
    | 'select'
    | 'switch';

export type FieldValues = Record<string, unknown>;

export type Field = {
    name: string;
    label: string;
    type: FieldType;
    required?: boolean;
    placeholder?: string;
    help?: string;
    options?: FieldOption[];
    /** Span both columns of the two-column form grid. */
    wide?: boolean;
    disabledOnEdit?: boolean;
    /** Leading icon override. Otherwise chosen from the field type, then the field name. */
    icon?: Component;
    /** Character limits for text-like fields. */
    minLength?: number;
    maxLength?: number;
    /** Value limits for `number` fields. */
    min?: number;
    max?: number;
    validate?: (value: unknown, values: FieldValues) => string | undefined;
};
