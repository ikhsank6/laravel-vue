import { z } from 'zod';

// Schemas for the account settings forms and the admin About form. Wording matches
// lib/schemas.ts. The server stays the final judge; these catch obvious mistakes first.
// Use `firstErrors` from lib/schemas.ts to turn a failed parse into one message per field.

const emailField = z
    .string()
    .trim()
    .min(1, 'Email wajib diisi.')
    .pipe(z.email('Format email belum benar. Contoh: nama@lembaga.id'));

export const profileSchema = z.object({
    name: z.string().trim().min(1, 'Nama wajib diisi.'),
    email: emailField,
});

export type ProfileInput = z.infer<typeof profileSchema>;

// Server rule: Password::min(8) at minimum (stricter in production). Only the length is mirrored.
export const passwordChangeSchema = z
    .object({
        current_password: z.string().min(1, 'Kata sandi saat ini wajib diisi.'),
        password: z
            .string()
            .min(1, 'Kata sandi baru wajib diisi.')
            .min(8, 'Kata sandi minimal 8 karakter.'),
        password_confirmation: z
            .string()
            .min(1, 'Konfirmasi kata sandi wajib diisi.'),
    })
    .refine((data) => data.password === data.password_confirmation, {
        message: 'Konfirmasi kata sandi tidak sama.',
        path: ['password_confirmation'],
    });

export type PasswordChangeInput = z.infer<typeof passwordChangeSchema>;

export const deleteAccountSchema = z.object({
    password: z.string().min(1, 'Kata sandi wajib diisi untuk menghapus akun.'),
});

export type DeleteAccountInput = z.infer<typeof deleteAccountSchema>;

// Optional text: empty is fine, otherwise the rule applies.
const optionalEmail = z
    .string()
    .trim()
    .refine(
        (value) => value === '' || z.email().safeParse(value).success,
        'Format email belum benar. Contoh: info@lembaga.id',
    );

const optionalPhone = z
    .string()
    .trim()
    .refine(
        (value) =>
            value === '' ||
            (/^[0-9+\-().\s]+$/.test(value) &&
                value.replace(/\D/g, '').length >= 6),
        'Nomor telepon belum benar. Pakai angka, spasi, +, - atau tanda kurung. Contoh: 021 1234 5678',
    );

const optionalLink = z
    .string()
    .trim()
    .refine(
        (value) => value === '' || /^(https?:\/\/|\/)/.test(value),
        'Tautan harus diawali https:// atau / untuk halaman di situs ini.',
    );

export const aboutSchema = z.object({
    name: z.string().trim().min(1, 'Nama organisasi wajib diisi.'),
    tagline: z.string().trim(),
    description: z.string().trim().min(1, 'Profil wajib diisi.'),
    address: z.string().trim(),
    email: optionalEmail,
    phone: optionalPhone,
    map_url: optionalLink,
});

export type AboutInput = z.infer<typeof aboutSchema>;
