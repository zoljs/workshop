<script setup lang="ts">
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Pencil, Plus, Sparkles, Trash2 } from 'lucide-vue-next';

defineProps<{
    workshops: Array<{
        id: number;
        name: string;
        archived: boolean;
        sessions: Array<{
            id: number;
            starts_at: string;
            max_capacity: number;
            status: string;
            bookings_sum_headcount: number | null;
        }>;
    }>;
}>();

function archive(id: number) {
    if (confirm('Archive this workshop?')) {
        router.patch(route('instructor.workshops.archive', id));
    }
}

function cancelSession(id: number) {
    if (
        confirm(
            'Cancel this session? All bookings will be marked as cancelled.',
        )
    ) {
        router.patch(route('instructor.sessions.cancel', id));
    }
}

function statusVariant(
    status?: string,
): 'default' | 'secondary' | 'destructive' | 'outline' {
    switch (status) {
        case 'active':
            return 'default';
        case 'cancelled_by_instructor':
            return 'destructive';
        default:
            return 'secondary';
    }
}

function statusLocalization(
    status?: string,
): 'Aktív' | 'Lemondva' | 'Megszünt' | '-' {
    switch (status) {
        case 'active':
            return 'Aktív';
        case 'cancelled_by_user ':
            return 'Lemondva';
        case 'cancelled_by_instructor':
            return 'Megszünt';
        default:
            return '-';
    }
}
</script>

<template>
    <Head title="Workshopjaim" />

    <AppLayout>
        <div class="min-h-screen bg-background">
            <div
                class="mx-auto max-w-7xl px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:px-8"
            >
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_0.4fr]">
                    <section class="flex flex-col gap-8">
                        <Button as-child variant="ghost" size="lg" class="w-24">
                            <Link :href="`/dashboard`">
                                <ArrowLeft />
                                Vissza
                            </Link>
                        </Button>

                        <!-- Header -->
                        <header
                            class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
                        >
                            <div class="max-w-2xl">
                                <h1
                                    class="text-4xl font-bold leading-tight text-primary md:text-6xl"
                                >
                                    Workshopjaim
                                </h1>
                                <p class="mt-1 text-muted-foreground">
                                    Kezeld a saját workshopjaid és az
                                    időpontokat!
                                </p>
                            </div>
                        </header>

                        <Separator />

                        <div
                            v-for="workshop in workshops"
                            :key="workshop.id"
                            class="mb-8"
                        >
                            <div class="mb-4 flex flex-row justify-between">
                                <h2
                                    class="text-2xl font-bold leading-tight text-primary md:text-4xl"
                                >
                                    {{ workshop.name }}
                                    {{ workshop.archived ? '(archived)' : '' }}
                                </h2>

                                <div class="flex flex-row gap-2">
                                    <Button
                                        as-child
                                        variant="outline"
                                        size="sm"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'instructor.workshops.edit',
                                                    workshop.id,
                                                )
                                            "
                                            ><Pencil
                                        /></Link>
                                    </Button>
                                    <Button
                                        v-if="!workshop.archived"
                                        variant="destructive"
                                        size="sm"
                                        @click="archive(workshop.id)"
                                    >
                                        <Trash2 />
                                    </Button>
                                </div>
                            </div>

                            <Card class="overflow-hidden">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Dátum</TableHead>
                                            <TableHead>Kapacitás</TableHead>
                                            <TableHead>Foglalt</TableHead>
                                            <TableHead>Státusz</TableHead>
                                            <TableHead />
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow
                                            v-for="session in workshop.sessions"
                                            :key="session.id"
                                        >
                                            <TableCell>{{
                                                session.starts_at
                                            }}</TableCell>
                                            <TableCell>{{
                                                session.max_capacity
                                            }}</TableCell>
                                            <TableCell>
                                                {{
                                                    session.bookings_sum_headcount ??
                                                    0
                                                }}
                                            </TableCell>
                                            <TableCell>
                                                <Badge
                                                    :variant="
                                                        statusVariant(
                                                            session.status,
                                                        )
                                                    "
                                                >
                                                    {{
                                                        statusLocalization(
                                                            session.status,
                                                        )
                                                    }}
                                                </Badge>
                                            </TableCell>
                                            <TableCell
                                                class="flex gap-2 justify-self-end"
                                            >
                                                <Button
                                                    as-child
                                                    variant="outline"
                                                    size="sm"
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'instructor.sessions.edit',
                                                                session.id,
                                                            )
                                                        "
                                                        ><Pencil
                                                    /></Link>
                                                </Button>
                                                <Button
                                                    v-if="
                                                        session.status ===
                                                        'active'
                                                    "
                                                    variant="destructive"
                                                    size="sm"
                                                    @click="
                                                        cancelSession(
                                                            session.id,
                                                        )
                                                    "
                                                >
                                                    <Trash2 />
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </Card>
                        </div>
                    </section>
                    <aside
                        class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start"
                    >
                        <Button as-child size="lg" class="h-20">
                            <Link :href="route('instructor.workshops.create')">
                                <Plus />
                                Új workshop hozzáadása
                            </Link>
                        </Button>
                        <Button
                            as-child
                            size="lg"
                            class="h-14"
                            variant="secondary"
                            ><Link :href="route('instructor.sessions.create')">
                                <Plus />
                                Új időpont hozzáadása
                            </Link>
                        </Button>

                        <!-- Cards -->
                        <Card
                            class="overflow-hidden bg-amber-700 p-2 text-orange-300"
                        >
                            <CardHeader class="pb-2">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-xl font-bold uppercase tracking-widest opacity-80"
                                    >
                                        Workshopjaid
                                    </span>
                                    <Sparkles />
                                </div>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div>
                                    <p class="mb-1 text-base opacity-70">
                                        Jelenlegi aktív
                                    </p>
                                    <p
                                        class="text-4xl font-extrabold text-orange-200"
                                    >
                                        {{ workshops.length }} db
                                    </p>
                                </div>
                            </CardContent>
                        </Card>
                    </aside>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
