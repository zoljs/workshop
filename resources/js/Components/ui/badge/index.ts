import type { VariantProps } from 'class-variance-authority';
import { cva } from 'class-variance-authority';

export { default as Badge } from './Badge.vue';

export const badgeVariants = cva(
    'inline-flex gap-1 items-center rounded-full h-6 px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
    {
        variants: {
            variant: {
                default: 'bg-cyan-200 hover:bg-cyan-200/80 text-primary',
                secondary:
                    'bg-orange-300 hover:bg-orange-300/80 text-orange-700',
                destructive: 'bg-destructive text-destructive-foreground',
                outline: 'text-foreground',
            },
        },
        defaultVariants: {
            variant: 'default',
        },
    },
);

export type BadgeVariants = VariantProps<typeof badgeVariants>;
