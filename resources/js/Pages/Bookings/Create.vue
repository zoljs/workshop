<script setup lang="ts">
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Separator } from '@/Components/ui/separator';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    session: {
        id: number;
        starts_at: string;
        max_capacity: number;
        spots_left: number;
        workshop: {
            id: number;
            name: string;
            price_per_person: number;
            duration_minutes: number;
        };
    };
    headcount: number;
}>();

const form = useForm({
    session_id: props.session.id,
    headcount: props.headcount,
});

const totalPrice = computed(
    () => props.session.workshop.price_per_person * form.headcount,
);

function formatDate(d: string) {
    return new Date(d).toLocaleString('hu-HU', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function submit() {
    form.post(route('bookings.store'));
}
</script>

<template>
    <Head :title="`Foglalás – ${session.workshop.name}`" />

    <AppLayout>
        <div class="min-h-screen bg-background">
            <div class="mx-auto max-w-lg px-6 pb-24 pt-32">
                <Button as-child variant="ghost" size="lg" class="mb-8 w-24">
                    <Link :href="`/workshops/${session.workshop.id}`">
                        <ArrowLeft />
                        Vissza
                    </Link>
                </Button>

                <!-- Summary card -->
                <div class="mb-4 rounded-2xl border border-border bg-card p-6">
                    <Badge
                        variant="outline"
                        class="mb-3 text-xs uppercase tracking-widest"
                    >
                        {{ session.workshop.duration_minutes }} perc
                    </Badge>
                    <h1 class="mb-4 text-2xl font-bold text-foreground">
                        {{ session.workshop.name }}
                    </h1>
                    <div class="space-y-2 text-sm text-muted-foreground">
                        <div class="flex justify-between">
                            <span>Időpont</span>
                            <span class="font-medium text-foreground">{{
                                formatDate(session.starts_at)
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Szabad helyek</span>
                            <span class="font-medium text-foreground">{{
                                session.spots_left
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ár / fő</span>
                            <span class="font-medium text-foreground">
                                {{
                                    session.workshop.price_per_person.toLocaleString(
                                        'hu-HU',
                                    )
                                }}
                                Ft
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Booking form -->
                <div class="rounded-2xl border border-border bg-card p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <p
                                class="mb-3 text-xs font-bold uppercase tracking-widest text-muted-foreground"
                            >
                                Hány főre foglalsz?
                            </p>
                            <div class="flex items-center gap-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="icon"
                                    :disabled="form.headcount <= 1"
                                    @click="form.headcount--"
                                >
                                    −
                                </Button>
                                <span
                                    class="w-8 text-center text-2xl font-bold"
                                >
                                    {{ form.headcount }}
                                </span>
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="icon"
                                    :disabled="
                                        form.headcount >= session.spots_left
                                    "
                                    @click="form.headcount++"
                                >
                                    +
                                </Button>
                            </div>
                            <p
                                v-if="form.errors.headcount"
                                class="mt-2 text-xs text-destructive"
                            >
                                {{ form.errors.headcount }}
                            </p>
                        </div>

                        <Separator />

                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground"
                                >Fizetendő összesen</span
                            >
                            <span class="text-2xl font-bold text-foreground">
                                {{ totalPrice.toLocaleString('hu-HU') }} Ft
                            </span>
                        </div>

                        <Button
                            type="submit"
                            class="w-full"
                            size="lg"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing
                                    ? 'Foglalás folyamatban...'
                                    : 'Foglalás megerősítése'
                            }}
                        </Button>

                        <p class="text-center text-xs text-muted-foreground">
                            Ingyenes lemondás 48 órával a workshop előtt.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
