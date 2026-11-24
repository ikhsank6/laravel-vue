import type { VariantProps } from "class-variance-authority"
import { cva } from "class-variance-authority"

export { default as Badge } from "./Badge.vue"

// Badges are pills (owner's choice for status and counts). They stay functional: a real
// status, a real label, or a real number. The label always carries the meaning; color only
// reinforces it. `count` is the round numeric variant (8, 99, 20+).
export const badgeVariants = cva(
  "inline-flex items-center justify-center gap-1 rounded-full border text-xs font-semibold w-fit whitespace-nowrap shrink-0 [&>svg]:size-3.5 [&>svg]:pointer-events-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring transition-colors overflow-hidden",
  {
    variants: {
      variant: {
        default:
          "border-transparent bg-primary text-primary-foreground [a&]:hover:brightness-110",
        secondary:
          "border-transparent bg-secondary text-secondary-foreground ring-1 ring-inset ring-border [a&]:hover:bg-accent",
        destructive:
          "border-transparent bg-destructive text-destructive-foreground [a&]:hover:brightness-110",
        success:
          "border-transparent bg-success text-white dark:text-[#0a1a2e]",
        warning:
          "border-transparent bg-warning text-white dark:text-[#0a1a2e]",
        outline:
          "border-border bg-transparent text-foreground [a&]:hover:bg-accent",
      },
      size: {
        default: "px-2.5 py-0.5",
        count: "h-6 min-w-6 px-1.5 tabular-nums",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  },
)
export type BadgeVariants = VariantProps<typeof badgeVariants>
