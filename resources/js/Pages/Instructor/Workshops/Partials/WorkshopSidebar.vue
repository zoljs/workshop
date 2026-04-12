<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import { Link } from '@inertiajs/vue3';
import { Plus, Sparkles } from 'lucide-vue-next';

const props = defineProps<{
    workshop_create: string;
    activeWorkshopsCount: number;
    archivedWorkshopsCount: number;
}>();
</script>

<template>
    <aside class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start">
        <Button as-child size="lg" class="hidden h-20 lg:inline-flex">
            <Link :href="props.workshop_create">
                <Plus />
                Új workshop hozzáadása
            </Link>
        </Button>

        <Card class="overflow-hidden bg-amber-700 p-2 text-orange-300">
            <CardHeader class="pb-2">
                <div class="flex items-center justify-between">
                    <span
                        class="text-xl font-bold uppercase tracking-widest opacity-80"
                    >
                        Workshopok
                    </span>
                    <Sparkles />
                </div>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <p class="mb-1 text-base opacity-70">Jelenlegi aktív</p>
                    <p class="text-4xl font-extrabold text-orange-200">
                        {{ props.activeWorkshopsCount }} db
                    </p>
                </div>
            </CardContent>
            <CardContent class="space-y-4">
                <div>
                    <p class="mb-1 text-base opacity-70">Archivált</p>
                    <p class="text-4xl font-extrabold text-orange-200">
                        {{ props.archivedWorkshopsCount }} db
                    </p>
                </div>
            </CardContent>
        </Card>
    </aside>
</template>

<style scoped></style>
