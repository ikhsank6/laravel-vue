import type { VariantProps } from "class-variance-authority"
import { cva } from "class-variance-authority"

export { default as Button } from "./Button.vue"

// Shape and height come from surface tokens (--btn-radius, --btn-h): pill and 44px on the
// website, 8px and 36px in admin. One primary per screen (DESIGN.md).
export const buttonVariants = cva(
  "inline-flex items-center justify-center gap-2 whitespace-nowrap [border-radius:var(--btn-radius)] text-sm [font-weight:var(--btn-weight)] [min-height:var(--btn-h)] transition-[background-color,color,border-color,transform] duration-150 active:scale-[0.97] disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring aria-invalid:ring-2 aria-invalid:ring-destructive",
  {
    variants: {
      variant: {
        default:
          "bg-primary text-primary-foreground hover:brightness-110",
        destructive:
          "bg-destructive text-destructive-foreground hover:brightness-110",
        secondary:
          "border border-primary bg-transparent text-link hover:bg-primary/10",
        outline:
          "border border-input bg-card text-foreground hover:bg-muted",
        utility:
          "bg-foreground text-background hover:opacity-90",
        ghost:
          "text-muted-foreground hover:bg-muted hover:text-foreground",
        link: "text-link underline-offset-4 hover:underline",
      },
      size: {
        "default": "px-5 has-[>svg]:px-4",
        "sm": "[min-height:max(var(--btn-h)-4px,36px)] gap-1.5 px-4 has-[>svg]:px-3",
        "lg": "min-h-12 px-8 text-base has-[>svg]:px-6",
        "icon": "size-[var(--btn-h)] !px-0",
        "icon-sm": "size-9 !px-0",
        "icon-lg": "size-12 !px-0",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  },
)
export type ButtonVariants = VariantProps<typeof buttonVariants>
