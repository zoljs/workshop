<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    workshop: {
        id: number;
        name: string;
        description: string;
        price_per_person: number;
        duration_minutes: number;
        instructor: {
            name: string;
        };
        sessions: Array<{
            id: number;
            starts_at: string;
            max_capacity: number;
            bookings_count: number;
        }>;
    };
}>();

function spotsLeft(session: { max_capacity: number; bookings_count: number }) {
    return session.max_capacity - session.bookings_count;
}

function formatDate(d: string) {
    return new Date(d).toLocaleString('hu-HU', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

const imageUrl = `https://picsum.photos/seed/${props.workshop.id}/600/400`;
</script>

<template>
    <Card
        class="group flex flex-col overflow-hidden transition-all duration-300 hover:-translate-y-3"
    >
        <!-- Image -->
        <div class="relative h-48 overflow-hidden">
            <img
                :src="imageUrl"
                :alt="workshop.name"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
        </div>

        <!-- Content -->
        <CardContent class="flex flex-grow flex-col gap-3 p-8">
            <!-- Name -->
            <h2 class="text-lg font-bold leading-tight text-gray-900">
                {{ workshop.name }}
            </h2>

            <!-- Instructor -->
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg
                    class="h-4 w-4 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                </svg>
                <span>{{ workshop.instructor.name }}</span>
            </div>

            <!-- Sessions -->
            <div class="flex flex-col gap-1">
                <div
                    v-for="session in workshop.sessions"
                    :key="session.id"
                    class="flex items-center gap-2 text-sm text-gray-500"
                >
                    <svg
                        class="h-4 w-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    <span
                        >{{ formatDate(session.starts_at) }} ·
                        {{ spotsLeft(session) }} hely</span
                    >
                </div>
                <p
                    v-if="workshop.sessions.length === 0"
                    class="text-sm italic text-gray-400"
                >
                    Nincs közelgő időpont
                </p>
            </div>

            <!-- Duration -->
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg
                    class="h-4 w-4 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                <span>{{ workshop.duration_minutes }} perc</span>
            </div>

            <!-- Footer -->
            <div
                class="mt-auto flex items-center justify-between border-t border-gray-100 pt-4"
            >
                <span class="text-2xl font-bold text-primary">
                    {{ workshop.price_per_person.toLocaleString('hu-HU') }} Ft
                </span>
                <Button as-child size="lg">
                    <Link :href="`/workshops/${workshop.id}`"
                        >Időpontfoglalás</Link
                    >
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
