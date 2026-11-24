import { computed, onMounted, reactive, ref } from 'vue';
import type { ZodType } from 'zod';
import { firstErrors } from '@/lib/schemas';

/**
 * Client-side validation shared by every auth form. Run `validate` on `@submit.capture` of an
 * Inertia `<Form novalidate>`: it reads the named inputs, checks them with the zod schema, and on
 * failure blocks the request, shows the first message per field, focuses the first invalid field
 * and shakes the form once. The server stays the final judge: its errors render in the same place
 * through `message(field, serverError)`.
 *
 * Field names double as input ids (`#email`, `#password`), and the first field is focused on mount.
 */
export function useFormValidation<K extends string>(
    schema: ZodType,
    fields: readonly K[],
) {
    const clientErrors = reactive<Record<string, string | undefined>>({});
    const shaking = ref(false);

    const validate = (event: Event) => {
        const form = event.target as HTMLFormElement;
        const data = new FormData(form);
        const result = schema.safeParse(
            Object.fromEntries(
                fields.map((field) => [field, data.get(field) ?? '']),
            ),
        );

        for (const field of fields) {
            clientErrors[field] = undefined;
        }

        if (result.success) {
            return;
        }

        Object.assign(clientErrors, firstErrors<K>(result.error));

        event.preventDefault();
        event.stopImmediatePropagation();

        const first = fields.find((field) => clientErrors[field]);

        form.querySelector<HTMLElement>(`#${first}`)?.focus();

        shaking.value = false;
        requestAnimationFrame(() => {
            shaking.value = true;
            setTimeout(() => (shaking.value = false), 400);
        });
    };

    /** Client message wins, otherwise the server one. */
    const message = (field: K, server?: string) =>
        clientErrors[field] ?? server;

    /** aria-invalid and aria-describedby for an input, to `v-bind`. */
    const aria = (field: K, server?: string) => {
        const invalid = !!message(field, server);

        return {
            'aria-invalid': invalid,
            'aria-describedby': invalid ? `${field}-error` : undefined,
        };
    };

    const clear = (field: K) => {
        clientErrors[field] = undefined;
    };

    const hasProblem = computed(() =>
        fields.some((field) => clientErrors[field]),
    );

    onMounted(() => {
        document.getElementById(fields[0])?.focus();
    });

    return {
        clientErrors,
        shaking,
        validate,
        message,
        aria,
        clear,
        hasProblem,
    };
}
