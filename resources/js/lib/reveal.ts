import type { Directive } from 'vue';

/**
 * v-reveal: content below the fold settles in once when first scrolled to. Value is the
 * stagger index (60ms per step). Anything already on screen at mount, any browser without
 * IntersectionObserver, and anyone who prefers reduced motion gets the content untouched.
 */
let observer: IntersectionObserver | null = null;

const getObserver = (): IntersectionObserver => {
    observer ??= new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer?.unobserve(entry.target);
                }
            }
        },
        { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
    );

    return observer;
};

export const vReveal: Directive<HTMLElement, number | undefined> = {
    mounted(el, binding) {
        if (
            typeof window === 'undefined' ||
            !('IntersectionObserver' in window) ||
            window.matchMedia('(prefers-reduced-motion: reduce)').matches
        ) {
            return;
        }

        if (el.getBoundingClientRect().top < window.innerHeight * 0.92) {
            return;
        }

        el.style.setProperty(
            '--reveal-delay',
            `${(binding.value ?? 0) * 60}ms`,
        );
        el.classList.add('reveal');
        getObserver().observe(el);
    },
    unmounted(el) {
        observer?.unobserve(el);
    },
};
