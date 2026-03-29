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
import { diffForHumans } from '@/lib/utils';
import { Head, Link } from '@inertiajs/vue3';
import { Pencil, Plus, Sparkles } from 'lucide-vue-next';

const props = defineProps<{
    workshops: Array<{
        id: number;
        name: string;
        archived: boolean;
        edit_url: string;
        archive_url: string;
        sessions: Array<{
            id: number;
            starts_at: string;
            max_capacity: number;
            status: string;
            bookings_sum_headcount: number | null;
            edit_url: string;
            cancel_url: string;
        }>;
    }>;
    workshop_create: string;
    session_create: string;
    instructor?: {
        id: number;
        name: string;
    };
}>();

function statusVariant(
    status?: string,
): 'default' | 'secondary' | 'destructive' | 'outline' {
    switch (status) {
        case 'active':
            return 'default';
        case 'cancelled':
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
        case 'cancelled':
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
                        <!-- Header -->
                        <header
                            class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
                        >
                            <div class="max-w-2xl">
                                <h1
                                    class="text-4xl font-bold leading-tight text-primary md:text-6xl"
                                >
                                    {{
                                        instructor
                                            ? instructor?.name + ' workshopjai'
                                            : 'Workshopjaim'
                                    }}
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
                                    {{
                                        workshop.archived ? '(Archiválva)' : ''
                                    }}
                                </h2>

                                <div class="flex flex-row gap-2">
                                    <Button as-child variant="ghost" size="lg">
                                        <Link :href="workshop.edit_url"
                                            >Szerkesztés</Link
                                        >
                                    </Button>
                                </div>
                            </div>

                            <Card class="overflow-hidden">
                                <Table>
                                    <TableHeader>
                                        <TableHead class="w-2/5"
                                            >Dátum</TableHead
                                        >
                                        <TableHead class="w-16"
                                            >Kapacitás</TableHead
                                        >
                                        <TableHead class="w-16"
                                            >Foglalt</TableHead
                                        >
                                        <TableHead class="w-1/5"
                                            >Státusz</TableHead
                                        >
                                        <TableHead class="w-16" />
                                    </TableHeader>
                                    <TableBody
                                        v-if="workshop.sessions.length > 0"
                                    >
                                        <TableRow
                                            v-for="session in workshop.sessions"
                                            :key="session.id"
                                        >
                                            <TableCell>{{
                                                diffForHumans(session.starts_at)
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
                                                class="flex gap-2 justify-self-end text-right"
                                            >
                                                <Button
                                                    as-child
                                                    variant="outline"
                                                    size="sm"
                                                    v-if="
                                                        session.status !==
                                                        'cancelled'
                                                    "
                                                >
                                                    <Link
                                                        :href="session.edit_url"
                                                        ><Pencil
                                                    /></Link>
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                    <TableBody
                                        v-if="workshop.sessions.length <= 0"
                                    >
                                        <TableRow>
                                            <TableCell
                                                colspan="5"
                                                class="text-center text-muted-foreground"
                                            >
                                                Nincsenek még időpontok.
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
                            <Link :href="workshop_create">
                                <Plus />
                                Új workshop hozzáadása
                            </Link>
                        </Button>
                        <Button
                            as-child
                            size="lg"
                            class="h-14"
                            variant="secondary"
                        >
                            <Link :href="session_create">
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
