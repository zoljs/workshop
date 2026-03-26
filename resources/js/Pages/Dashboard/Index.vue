<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';

const props = defineProps<{
    user: {
        name: string;
        points: number;
    };
    upcomingBookings: Array<{
        id: number;
        headcount: number;
        amount_paid: number;
        session: {
            starts_at: string;
            workshop: {
                id: number;
                name: string;
                duration_minutes: number;
            };
        };
    }>;
}>();

function formatDate(dateStr: string) {
    return new Date(dateStr).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function isUpcoming(dateStr: string) {
    return new Date(dateStr) > new Date();
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="space-y-6 p-6">
            <!-- Welcome Header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight">
                    Welcome back, {{ user.name }}
                </h1>
                <p class="text-muted-foreground">
                    Here's an overview of your activity.
                </p>
            </div>

            <Separator />

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardDescription>Loyalty Points</CardDescription>
                        <CardTitle class="text-4xl">{{
                            user.points
                        }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">
                            Earn points with every booking.
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardDescription>Upcoming Workshops</CardDescription>
                        <CardTitle class="text-4xl">{{
                            upcomingBookings.length
                        }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">
                            Showing up to 3 nearest sessions.
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardDescription>Quick Actions</CardDescription>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-2">
                        <Button as-child variant="default">
                            <Link href="/workshops">Browse Workshops</Link>
                        </Button>
                        <Button as-child variant="outline">
                            <Link href="/dashboard/bookings"
                                >View All Bookings</Link
                            >
                        </Button>
                    </CardContent>
                </Card>
            </div>

            <!-- Upcoming Bookings Table -->
            <Card>
                <CardHeader>
                    <CardTitle>Upcoming Bookings</CardTitle>
                    <CardDescription
                        >Your next scheduled workshop sessions.</CardDescription
                    >
                </CardHeader>
                <CardContent>
                    <Table v-if="upcomingBookings.length > 0">
                        <TableHeader>
                            <TableRow>
                                <TableHead>Workshop</TableHead>
                                <TableHead>Date</TableHead>
                                <TableHead>Attendees</TableHead>
                                <TableHead>Amount Paid</TableHead>
                                <TableHead>Status</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="booking in upcomingBookings"
                                :key="booking.id"
                            >
                                <TableCell class="font-medium">
                                    <Link
                                        :href="`/workshops/${booking.session.workshop.id}`"
                                        class="hover:underline"
                                    >
                                        {{ booking.session.workshop.name }}
                                    </Link>
                                </TableCell>
                                <TableCell>{{
                                    formatDate(booking.session.starts_at)
                                }}</TableCell>
                                <TableCell>{{ booking.headcount }}</TableCell>
                                <TableCell
                                    >{{
                                        booking.amount_paid.toLocaleString(
                                            'en-US',
                                        )
                                    }}
                                    Ft</TableCell
                                >
                                <TableCell>
                                    <Badge
                                        :variant="
                                            isUpcoming(
                                                booking.session.starts_at,
                                            )
                                                ? 'default'
                                                : 'secondary'
                                        "
                                    >
                                        {{
                                            isUpcoming(
                                                booking.session.starts_at,
                                            )
                                                ? 'Upcoming'
                                                : 'Past'
                                        }}
                                    </Badge>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <div v-else class="py-10 text-center text-muted-foreground">
                        <p class="text-sm">You have no upcoming bookings.</p>
                        <Button as-child variant="link" class="mt-2">
                            <Link href="/workshops">Book a workshop →</Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
