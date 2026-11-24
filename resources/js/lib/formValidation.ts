import { z } from 'zod';
import type { Field, FieldValues } from '@/types';

// Zod schema built from a Field[] so admin CRUD dialogs validate like the auth forms:
// same wording style as lib/schemas.ts, first message per field, server stays the final judge.

export type FieldErrors = Record<string, string>;

export type FieldValidation = {
    errors: FieldErrors;
    /** Name of the first invalid field in form order, for focusing. */
    firstInvalidName: string | null;
};

function asText(value: unknown): string {
    return typeof value === 'string' || typeof value === 'number'
        ? String(value)
        : '';
}

const isUrl = (value: string) => /^(https?:\/\/\S+|\/\S*)$/.test(value);
const isNumber = (value: string) =>
    value.trim() !== '' && Number.isFinite(Number(value));
const isDate = (value: string) =>
    /^\d{4}-\d{2}-\d{2}$/.test(value) && !Number.isNaN(Date.parse(value));

/** Format rules pass for an empty value; "required" is checked separately. */
function fieldSchema(field: Field, values: FieldValues): z.ZodType<string> {
    const label = field.label;
    let schema = z.string().trim();

    if (field.required) {
        schema = schema.min(1, `${label} wajib diisi.`);
    }

    const optional = (test: (value: string) => boolean) => (value: string) =>
        value === '' || test(value);

    if (field.type === 'email') {
        schema = schema.refine(
            optional((v) => z.email().safeParse(v).success),
            'Format email belum benar. Contoh: nama@lembaga.id',
        );
    } else if (field.type === 'url') {
        schema = schema.refine(
            optional(isUrl),
            'Tautan harus diawali https:// atau / untuk halaman di situs ini.',
        );
    } else if (field.type === 'date') {
        schema = schema.refine(
            optional(isDate),
            `${label} belum benar. Gunakan tanggal yang valid.`,
        );
    } else if (field.type === 'number') {
        schema = schema.refine(
            optional(isNumber),
            `${label} harus berupa angka.`,
        );

        if (field.min !== undefined) {
            const min = field.min;

            schema = schema.refine(
                optional((v) => !isNumber(v) || Number(v) >= min),
                `${label} minimal ${min}.`,
            );
        }

        if (field.max !== undefined) {
            const max = field.max;

            schema = schema.refine(
                optional((v) => !isNumber(v) || Number(v) <= max),
                `${label} maksimal ${max}.`,
            );
        }
    }

    if (field.type !== 'number') {
        if (field.minLength !== undefined) {
            const min = field.minLength;

            schema = schema.refine(
                optional((v) => v.length >= min),
                `${label} minimal ${min} karakter.`,
            );
        }

        if (field.maxLength !== undefined) {
            schema = schema.max(
                field.maxLength,
                `${label} maksimal ${field.maxLength} karakter.`,
            );
        }
    }

    const custom = field.validate;

    if (!custom) {
        return schema;
    }

    return schema.superRefine((_, ctx) => {
        const message = custom(values[field.name], values);

        if (message) {
            ctx.addIssue({ code: 'custom', message });
        }
    });
}

/** Zod object schema for the given fields; switches carry no text rules. */
export function buildFieldsSchema(fields: Field[], values: FieldValues) {
    return z.object(
        Object.fromEntries(
            fields
                .filter((field) => field.type !== 'switch')
                .map((field) => [field.name, fieldSchema(field, values)]),
        ),
    );
}

export function validateFields(
    fields: Field[],
    values: FieldValues,
): FieldValidation {
    const input = Object.fromEntries(
        fields.map((field) => [field.name, asText(values[field.name])]),
    );
    const result = buildFieldsSchema(fields, values).safeParse(input);
    const errors: FieldErrors = {};

    if (!result.success) {
        for (const issue of result.error.issues) {
            errors[String(issue.path[0])] ??= issue.message;
        }
    }

    // Switches only run their custom rule (they have no text to check).
    for (const field of fields) {
        if (field.type === 'switch' && field.validate) {
            const message = field.validate(values[field.name], values);

            if (message) {
                errors[field.name] ??= message;
            }
        }
    }

    return {
        errors,
        firstInvalidName:
            fields.find((field) => errors[field.name])?.name ?? null,
    };
}
