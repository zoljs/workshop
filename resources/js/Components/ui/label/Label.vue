<script setup lang="ts">
import { cn } from '@/lib/utils';
import { reactiveOmit } from '@vueuse/core';
import type { LabelProps } from 'reka-ui';
import { Label } from 'reka-ui';
import type { HTMLAttributes } from 'vue';

const props = defineProps<LabelProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = reactiveOmit(props, 'class');
</script>

<template>
    <Label
        v-bind="delegatedProps"
        :class="cn('text-lg font-semibold text-primary', props.class)"
    >
        <slot />
    </Label>
</template>
