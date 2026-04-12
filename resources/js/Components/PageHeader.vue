<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Separator } from '@/Components/ui/separator';
import { goBack } from '@/lib/utils';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    title: string;
    description?: string;
    hasBack?: boolean;
}>();
</script>
<template>
    <header class="flex flex-col gap-8">
        <Button
            v-if="props.hasBack"
            @click="goBack"
            variant="ghost"
            size="lg"
            class="w-24"
        >
            <ArrowLeft />
            Vissza
        </Button>

        <div
            class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
        >
            <div class="max-w-2xl">
                <h1
                    class="text-4xl font-bold leading-tight text-primary md:text-6xl"
                >
                    {{ props.title }}
                </h1>
                <p v-if="props.description" class="mt-1 text-muted-foreground">
                    {{ props.description }}
                </p>
            </div>
        </div>

        <Separator />
    </header>
</template>

<style scoped></style>
