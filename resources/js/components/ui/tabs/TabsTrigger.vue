<script setup lang="ts">
import type { TabsTriggerProps } from "reka-ui"
import type { HTMLAttributes } from "vue"
import { reactiveOmit } from "@vueuse/core"
import { cn } from "@/lib/utils"
import { TabsTrigger, useForwardProps } from "reka-ui"

const props = defineProps<TabsTriggerProps & { class?: HTMLAttributes["class"] }>()

const delegatedProps = reactiveOmit(props, "class")

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <TabsTrigger
    data-slot="tabs-trigger"
    :class="cn(
      `data-[state=active]:border-primary data-[state=active]:text-foreground -mb-px inline-flex min-h-11 sm:min-h-9 items-center justify-center gap-1.5 border-b-2 border-transparent px-3 text-sm font-semibold whitespace-nowrap transition-colors hover:text-foreground disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4`,
      props.class,
    )"
    v-bind="forwardedProps"
  >
    <slot />
  </TabsTrigger>
</template>
