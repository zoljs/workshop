<script setup lang="ts">
import { cn } from '@/lib/utils';
import { reactiveOmit } from '@vueuse/core';
import type { TabsListProps } from 'reka-ui';
import { TabsList } from 'reka-ui';
import type { HTMLAttributes } from 'vue';

const props = defineProps<
    TabsListProps & { class?: HTMLAttributes['class'] }
>();

const delegatedProps = reactiveOmit(props, 'class');
</script>

<template>
    <TabsList
        v-bind="delegatedProps"
        :class="
            cn(
                'inline-flex h-14 items-center justify-center rounded-3xl bg-muted p-1',
                props.class,
            )
        "
    >
        <slot />
    </TabsList>
</template>
