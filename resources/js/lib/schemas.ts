import { z } from 'zod';

// Form schemas live here so rules and messages are written once, in Indonesian. The server
// stays the final judge; these catch the obvious mistakes before a request is sent.
export const loginSchema = z.object({
    email: z
        .string()
        .trim()
        .min(1, 'Email wajib diisi.')
        .pipe(z.email('Format email belum benar. Contoh: nama@lembaga.id')),
    password: z.string().min(1, 'Kata sandi wajib diisi.'),
});

export type LoginInput = z.infer<typeof loginSchema>;

/** First message per field, so a field never shows two errors at once. */
export function firstErrors<K extends string>(
    error: z.ZodError,
): Partial<Record<K, string>> {
    const result: Partial<Record<K, string>> = {};

    for (const issue of error.issues) {
        const key = String(issue.path[0]) as K;

        result[key] ??= issue.message;
    }

    return result;
}

const emailField = z
    .string()
    .trim()
    .min(1, 'Email wajib diisi.')
    .pipe(z.email('Format email belum benar. Contoh: nama@lembaga.id'));

// Server rule: Password::min(8) at minimum (stricter in production). Only the length is mirrored here.
const newPasswordField = z
    .string()
    .min(1, 'Kata sandi wajib diisi.')
    .min(8, 'Kata sandi minimal 8 karakter.');

const confirmationField = z
    .string()
    .min(1, 'Konfirmasi kata sandi wajib diisi.');

const passwordsMatch = (data: {
    password: string;
    password_confirmation: string;
}) => data.password === data.password_confirmation;

export const registerSchema = z
    .object({
        name: z.string().trim().min(1, 'Nama wajib diisi.'),
        email: emailField,
        password: newPasswordField,
        password_confirmation: confirmationField,
    })
    .refine(passwordsMatch, {
        message: 'Konfirmasi kata sandi tidak sama.',
        path: ['password_confirmation'],
    });

export type RegisterInput = z.infer<typeof registerSchema>;

export const forgotPasswordSchema = z.object({ email: emailField });

export type ForgotPasswordInput = z.infer<typeof forgotPasswordSchema>;

export const resetPasswordSchema = z
    .object({
        password: newPasswordField,
        password_confirmation: confirmationField,
    })
    .refine(passwordsMatch, {
        message: 'Konfirmasi kata sandi tidak sama.',
        path: ['password_confirmation'],
    });

export type ResetPasswordInput = z.infer<typeof resetPasswordSchema>;

export const confirmPasswordSchema = z.object({
    password: z.string().min(1, 'Kata sandi wajib diisi.'),
});

export type ConfirmPasswordInput = z.infer<typeof confirmPasswordSchema>;
