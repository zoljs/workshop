<script setup lang="ts">
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
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
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    users: Array<{
        id: number;
        name: string;
        email: string;
        points: number;
        banned_until: string | null;
        created_at: string;
        roles: string[];
    }>;
}>();

function formatDate(d: string) {
    return new Date(d).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}

function isBanned(bannedUntil: string | null) {
    if (!bannedUntil) return false;
    return new Date(bannedUntil) > new Date();
}

const instructors = props.users.filter((u) => u.roles.includes('instructor'));
const regularUsers = props.users.filter((u) => !u.roles.includes('instructor'));
</script>

<template>
    <Head title="Felhasználók" />

    <AppLayout>
        <div
            class="mx-auto max-w-7xl space-y-12 px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:px-8"
        >
            <!-- Header -->
            <header
                class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
            >
                <div class="max-w-2xl">
                    <h1
                        class="text-4xl font-bold leading-tight text-primary md:text-6xl"
                    >
                        Felhasználók
                    </h1>
                    <p class="mt-1 text-muted-foreground">
                        Kezeld a regisztrált felhasználók hozzáférését és az
                        óraadók workshopjait.
                    </p>
                </div>
            </header>

            <Separator />

            <!-- Instructors -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold">Óraadók</h2>
                </div>

                <Card>
                    <Table v-if="instructors.length > 0">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-1/4">Név</TableHead>
                                <TableHead class="w-1/4">Email</TableHead>
                                <TableHead class="w-1/6">Státusz</TableHead>
                                <TableHead class="w-1/6"
                                    >Csatlakozott</TableHead
                                >
                                <TableHead class="w-1/6 text-right" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="user in instructors"
                                :key="user.id"
                            >
                                <TableCell class="font-medium">{{
                                    user.name
                                }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>
                                    <Badge
                                        v-if="isBanned(user.banned_until)"
                                        variant="destructive"
                                        >Felfüggesztve</Badge
                                    >
                                    <Badge v-else variant="default"
                                        >Aktív</Badge
                                    >
                                </TableCell>
                                <TableCell>{{
                                    formatDate(user.created_at)
                                }}</TableCell>
                                <TableCell class="text-right">
                                    <Button
                                        as-child
                                        variant="outline"
                                        size="sm"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'admin.instructor.workshops',
                                                    user.id,
                                                )
                                            "
                                        >
                                            Workshopok megtekintése
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <CardContent
                        v-else
                        class="py-8 text-center text-sm text-muted-foreground"
                    >
                        No instructors found.
                    </CardContent>
                </Card>
            </div>

            <!-- Regular Users -->
            <div class="space-y-4">
                <h2 class="text-2xl font-bold">Felhasználók</h2>

                <Card>
                    <Table v-if="regularUsers.length > 0">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-1/5">Név</TableHead>
                                <TableHead class="w-1/5">Email</TableHead>
                                <TableHead class="w-1/5">Pontok</TableHead>
                                <TableHead class="w-1/5">Státusz</TableHead>
                                <TableHead class="w-1/5"
                                    >Csatlakozott</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="user in regularUsers"
                                :key="user.id"
                            >
                                <TableCell class="font-medium">{{
                                    user.name
                                }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>{{ user.points }}</TableCell>
                                <TableCell>
                                    <Badge
                                        v-if="isBanned(user.banned_until)"
                                        variant="destructive"
                                        >Felfüggesztve</Badge
                                    >
                                    <Badge v-else variant="default"
                                        >Aktív</Badge
                                    >
                                </TableCell>
                                <TableCell>{{
                                    formatDate(user.created_at)
                                }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <CardContent
                        v-else
                        class="py-8 text-center text-sm text-muted-foreground"
                    >
                        Nincsenek felhasználók.
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
